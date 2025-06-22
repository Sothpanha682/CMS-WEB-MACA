<div class="container py-4">
    <h1 class="fw-bold text-danger mb-4">Contact Us</h1>
    
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h3 class="text-danger mb-4">Get in Touch</h3>
                    <p class="mb-4">Have questions about our programs or need more information? We're here to help. Fill out the form below or contact us directly.</p>
                    
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_submit'])) {
                        $name = sanitize($_POST['name']);
                        $email = sanitize($_POST['email']);
                        $subject = sanitize($_POST['subject']);
                        $message = sanitize($_POST['message']);
                        
                        // Save message to database
                        try {
                            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, is_read, created_at) VALUES (:name, :email, :subject, :message, 0, NOW())");
                            $stmt->bindParam(':name', $name);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':subject', $subject);
                            $stmt->bindParam(':message', $message);
                            $stmt->execute();
                            
                            echo '<div class="alert alert-success">Thank you for your message! We will get back to you soon.</div>';
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger">Sorry, there was an error sending your message. Please try again later.</div>';
                        }
                    }
                    ?>
                    
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" name="contact_submit" class="btn btn-danger">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h3 class="text-danger mb-4">Contact Information</h3>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-map-marker-alt text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h5>Our Location</h5>
                            <p>Wealth Mainson Building Floor 6,<br> Room 30, Sk.Chroychangva Kh.Chroy Changva</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-phone-alt text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h5>Phone</h5>
                            <p>070 887 332</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-envelope text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p>info@mymaca.asia</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Office Hours</h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Monday - Friday:</td>
                                    <td>8:30 AM - 5:00 PM</td>
                                </tr>
                                <tr>
                                    <td>Saturday:</td>
                                    <td>Closed</td>
                                </tr>
                                <tr>
                                    <td>Sunday:</td>
                                    <td>Closed</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-danger mb-4">Our Location</h3>
                    <div class="ratio ratio-21x9">
                        <!-- Replace with actual Google Maps embed code -->
                       <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d290.50308253497275!2d104.92800056981628!3d11.585655274506982!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1skm!2skh!4v1746897638323!5m2!1skm!2skh" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
