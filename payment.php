<?php
session_start();

$showtime_id = isset($_GET['showtime_id']) ? intval($_GET['showtime_id']) : 0;
$ticket_count = isset($_GET['ticket_count']) ? intval($_GET['ticket_count']) : 1;
$seat_ids = isset($_GET['seat_ids']) ? explode(',', $_GET['seat_ids']) : [];
$total_amount = isset($_GET['total_amount']) ? floatval($_GET['total_amount']) : 0.0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Cinema Booking</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <h1><i class="fas fa-money-bill"></i> Payment System</h1>
        <?php include 'includes/nav.php'; ?>
        
        <div class="section-header">
            <h2><i class="fas fa-credit-card"></i> Payment</h2>
        </div>
        
        <div style="max-width: 600px; margin: 0 auto;">
            
            <div class="form-card">
                <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
                
                <form>
                    <input type="hidden" name="showtime_id" value="<?php echo htmlspecialchars($showtime_id); ?>">
                    <input type="hidden" name="ticket_count" value="<?php echo htmlspecialchars($ticket_count); ?>">
                    <input type="hidden" name="seat_ids" value="<?php echo htmlspecialchars(implode(',', $seat_ids)); ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card_number"><i class="fas fa-credit-card"></i> Card Number</label>
                            <input type="text" 
                                   name="card_number" 
                                   id="card_number"
                                   placeholder="1234 5678 9012 3456"
                                   required 
                                   maxlength="19" 
                                   pattern="\d{16,19}">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry"><i class="fas fa-calendar-alt"></i> Expiry Date</label>
                            <input type="text" 
                                   name="expiry" 
                                   id="expiry"
                                   placeholder="MM/YY"
                                   required 
                                   maxlength="5" 
                                   pattern="\d{2}/\d{2}">
                        </div>
                        <div class="form-group">
                            <label for="cvc"><i class="fas fa-lock"></i> CVC</label>
                            <input type="text" 
                                   name="cvc" 
                                   id="cvc"
                                   placeholder="123"
                                   required 
                                   maxlength="4" 
                                   pattern="\d{3,4}">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary" id="pay-btn">
                            <i class="fas fa-lock"></i> Pay €<?php echo htmlspecialchars(number_format($total_amount, 2)); ?>
                        </button>
                        <button type="button" class="btn-secondary" onclick="history.back()">
                            <i class="fas fa-arrow-left"></i> Back to Booking
                        </button>
                    </div>
                    <div class="msg" id="purchase-msg"></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
            if (formattedValue.length <= 19) {
                e.target.value = formattedValue;
            }
        });

        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        document.getElementById('cvc').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });

        function checkCardNumber(cardNumber) {
            return /^\d{16,19}$/.test(cardNumber.replace(/\s/g, ''));
        }

        function checkExpiry(expiry) {
            return /^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry);
        }
        function isExpiryExpired(expiry) {
            const [mm, yy] = expiry.split('/');
            const expMonth = parseInt(mm, 10);
            const expYear = 2000 + parseInt(yy, 10);

            const now = new Date();
            const thisMonth = now.getMonth() + 1; 
            const thisYear = now.getFullYear();


            if (expYear < thisYear) return true;
            if (expYear === thisYear && expMonth < thisMonth) return true;
            return false;
        }

        function checkCVC(cvc) {
            return /^\d{3,4}$/.test(cvc);
        }

        document.getElementById("pay-btn").addEventListener("click", function(e) {
            e.preventDefault();
            const showtimeId = <?php echo json_encode($showtime_id); ?>;
            const ticketCount = <?php echo json_encode($ticket_count); ?>;
            const selectedSeats = <?php echo json_encode($seat_ids); ?>;
            const msgEl = document.getElementById('purchase-msg');
            msgEl.textContent = '';

            const cardNumber = document.getElementById('card_number').value;
            const expiry = document.getElementById('expiry').value;
            const cvc = document.getElementById('cvc').value;

            if (!checkCardNumber(cardNumber)) {
                msgEl.textContent = 'Invalid card number. Please enter a valid 16-19 digit card number.';
                return;
            }
            if (!checkExpiry(expiry)) {
                msgEl.textContent = 'Invalid expiry date. Please use MM/YY format.';
                return;
            }
            if (isExpiryExpired(expiry)) {
                msgEl.textContent = 'Card has expired. Please use a valid card.';
                return;
            }
            if (!checkCVC(cvc)) {
                msgEl.textContent = 'Invalid CVC. Please enter a 3 or 4 digit CVC.';
                return;
            }
            

            console.log("Seleceted Seats:", selectedSeats);
            
            fetch('api/process_ticket.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    showtime_id: showtimeId,
                    ticket_count: ticketCount,
                    seat_ids: selectedSeats
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    msgEl.textContent = 'Purchase successful!';
                    setTimeout(() => {
                        window.location.href = 'my_tickets.php';
                    }, 1500);
                } else {
                    showMessage(result.message || 'Purchase failed', 'error');
                }
            })
            .catch(error => {
                showMessage('An error occurred during purchase', 'error');
            });
        });
    </script>
</body>
</html>