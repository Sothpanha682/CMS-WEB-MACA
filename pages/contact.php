<div class="container py-4">
    <h1 class="fw-bold text-danger mb-4"><?php echo getLangText('Contact Us', 'ទាក់ទង​មក​ពួក​យើង'); ?></h1>
    
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h3 class="text-danger mb-4"><?php echo getLangText('Get in Touch', 'ទាក់ទង'); ?></h3>
                    <p class="mb-4"><?php echo getLangText("Have questions about our programs or need more information? We're here to help. Fill out the form below or contact us directly.", "មានសំណួរអំពីកម្មវិធីរបស់យើង ឬត្រូវការព័ត៌មានបន្ថែម? យើងនៅទីនេះដើម្បីជួយ។ បំពេញទម្រង់ខាងក្រោម ឬទាក់ទងមកយើងដោយផ្ទាល់។"); ?></p>
                    
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_submit'])) {
                        $name = sanitize($_POST['name']);
                        $email = sanitize($_POST['email']);
                        $subject = sanitize($_POST['subject']);
                        $message = sanitize($_POST['message']);

                        $allowed_domains = [
                            'gmail.com', 'googlemail.com', // Gmail
                            'yahoo.com', 'ymail.com', // Yahoo Mail
                            'outlook.com', 'hotmail.com', 'live.com', 'msn.com', // Outlook.com
                            'protonmail.com', 'proton.me' // ProtonMail
                        ];

                        $email_domain = strtolower(substr(strrchr($email, "@"), 1));

                        if (!in_array($email_domain, $allowed_domains)) {
                            echo '<div class="alert alert-danger">' . getLangText('Sorry, we only accept messages from Gmail, Yahoo Mail, Outlook.com, and ProtonMail addresses.', 'សូមអភ័យទោស យើងទទួលតែសារពីអាសយដ្ឋាន Gmail, Yahoo Mail, Outlook.com, និង ProtonMail ប៉ុណ្ណោះ។') . '</div>';
                        } else {
                            // Save message to database
                            try {
                                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, is_read, created_at) VALUES (:name, :email, :subject, :message, 0, NOW())");
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':email', $email);
                                $stmt->bindParam(':subject', $subject);
                                $stmt->bindParam(':message', $message);
                                $stmt->execute();
                                
                                echo '<div class="alert alert-success">' . getLangText('Thank you for your message! We will get back to you soon.', 'សូមអរគុណចំពោះសាររបស់អ្នក! យើងនឹងទាក់ទងទៅអ្នកវិញឆាប់ៗ។') . '</div>';
                            } catch(PDOException $e) {
                                echo '<div class="alert alert-danger">' . getLangText('Sorry, there was an error sending your message. Please try again later.', 'សូមអភ័យទោស មានបញ្ហាពេលផ្ញើសាររបស់អ្នក។ សូមព្យាយាមម្តងទៀតនៅពេលក្រោយ។') . '</div>';
                            }
                        }
                    }
                    ?>
                    
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label"><?php echo getLangText('Your Name', 'ឈ្មោះ​របស់​អ្នក'); ?></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo getLangText('Email Address', 'អាសយដ្ឋានអ៊ីមែល'); ?></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label"><?php echo getLangText('Subject', 'ប្រធានបទ'); ?></label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label"><?php echo getLangText('Message', 'សារ'); ?></label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" name="contact_submit" class="btn btn-danger"><?php echo getLangText('Send Message', 'ផ្ញើសារ'); ?></button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h3 class="text-danger mb-4"><?php echo getLangText('Contact Information', 'ព័ត៌មានទំនាក់ទំនង'); ?></h3>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-map-marker-alt text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h5><?php echo getLangText('Our Location', 'ទីតាំងរបស់យើង'); ?></h5>
                            <p><?php echo getLangText('Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'អគារ Wealth Mainson ជាន់ទី 6, បន្ទប់ 30, សង្កាត់ជ្រោយចង្វារ ខណ្ឌជ្រោយចង្វារ'); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-phone-alt text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h5><?php echo getLangText('Phone', 'ទូរស័ព្ទ'); ?></h5>
                            <p>070 887 332</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-envelope text-danger fa-2x"></i>
                        </div>
                        <div>
                            <h5><?php echo getLangText('Email', 'អ៊ីមែល'); ?></h5>
                            <p>info@mymaca.asia</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5><?php echo getLangText('Office Hours', 'ម៉ោងធ្វើការ'); ?></h5>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><?php echo getLangText('Monday - Friday:', 'ច័ន្ទ - សុក្រ:'); ?></td>
                                    <td><?php echo getLangText('8:30 AM - 5:00 PM', '8:30 ព្រឹក - 5:00 ល្ងាច'); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo getLangText('Saturday:', 'ថ្ងៃសៅរ៍:'); ?></td>
                                    <td><?php echo getLangText('Closed', 'បិទ'); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo getLangText('Sunday:', 'ថ្ងៃអាទិត្យ:'); ?></td>
                                    <td><?php echo getLangText('Closed', 'បិទ'); ?></td>
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
                    <h3 class="text-danger mb-4"><?php echo getLangText('Our Location', 'ទីតាំងរបស់យើង'); ?></h3>
                    <div class="ratio ratio-21x9">
                        <!-- Replace with actual Google Maps embed code -->
                       <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d290.50308253497275!2d104.92800056981628!3d11.585655274506982!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1skm!2skh!4v1746897638323!5m2!1skm!2skh" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
