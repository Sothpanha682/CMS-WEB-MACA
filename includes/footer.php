</main>
    
<footer class="bg-danger text-white py-4 mt-5 w-100">
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-4">
                <h5><?php echo getLangText('MACA', 'MACA'); ?></h5>
                <p><?php echo getLangText('Empowering education for a better future. We provide comprehensive educational resources and career guidance.', 'លើកកម្ពស់ការអប់រំសម្រាប់អនាគតកាន់តែប្រសើរ។ យើងផ្តល់ធនធានអប់រំដ៏ទូលំទូលាយ និងការណែនាំអាជីព។'); ?></p>
                <div class="social-icons">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h5><?php echo getLangText('Quick Links', 'តំណភ្ជាប់រហ័ស'); ?></h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white"><?php echo getLangText('Home', 'ទំព័រដើម'); ?></a></li>
                    <li><a href="index.php?page=about" class="text-white"><?php echo getLangText('About Us', 'អំពីយើង'); ?></a></li>
                    <li><a href="index.php?page=announcements" class="text-white"><?php echo getLangText('Announcements', 'សេចក្តីប្រកាស'); ?></a></li>
                    <li><a href="index.php?page=news" class="text-white"><?php echo getLangText('News', 'ព័ត៌មាន'); ?></a></li>
                    <li><a href="index.php?page=contact" class="text-white"><?php echo getLangText('Contact Us', 'ទំនាក់ទំនងយើង'); ?></a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5><?php echo getLangText('Contact Us', 'ទំនាក់ទំនងយើង'); ?></h5>
                <address class="text-white">
                    <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo getLangText('Wealth Mainson Building Floor 6, Room 30, Sk.Chroychangva Kh.Chroy Changva', 'អគារ Wealth Mainson ជាន់ទី ៦ បន្ទប់ ៣០ សង្កាត់ជ្រោយចង្វារ ខណ្ឌជ្រោយចង្វារ'); ?></p>
                    <p><i class="fas fa-phone me-2"></i> <?php echo getLangText('070 887 332', '០៧០ ៨៨៧ ៣៣២'); ?></p>
                    <p><i class="fas fa-envelope me-2"></i> info@mymaca.asia</p>
                </address>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo getLangText('MACA. All rights reserved.', 'MACA. រក្សាសិទ្ធិគ្រប់យ៉ាង.'); ?></p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="assets/js/script.js"></script>

<!-- Include AI Assistant -->
<?php include 'includes/ai-assistant.php'; ?>

<!-- Include AI Assistant JS -->
<script src="assets/js/ai-assistant.js"></script>
</body>
</html>
