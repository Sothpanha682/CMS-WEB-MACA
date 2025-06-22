<?php


// Get major ID from URL
$major_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get major details from database
$query = "SELECT * FROM popular_majors WHERE id = $major_id";
$result = mysqli_query($conn, $query);

// Check if major exists
if (mysqli_num_rows($result) == 0) {
    echo '<div class="container my-5"><div class="alert alert-danger">Major not found!</div></div>';
    exit;
}

$major = mysqli_fetch_assoc($result);

// Get related majors
$related_query = "SELECT id, title, university, image FROM popular_majors WHERE id != $major_id ORDER BY RAND() LIMIT 3";
$related_result = mysqli_query($conn, $related_query);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <?php if (!empty($major['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $major['image'])): ?>
                    <img src="<?php echo $major['image']; ?>" class="card-img-top" alt="<?php echo $major['title']; ?>" style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h1 class="card-title"><?php echo $major['title']; ?></h1>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger me-2">Major</span>
                        <span class="text-muted"><i class="fas fa-university me-1"></i> <?php echo $major['university']; ?></span>
                    </div>
                    
                    <?php if (!empty($major['salary_potential']) || !empty($major['duration'])): ?>
                        <div class="alert alert-light border mb-4">
                            <div class="row">
                                <?php if (!empty($major['salary_potential'])): ?>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong><i class="fas fa-money-bill-wave me-2"></i>Salary Potential:</strong></p>
                                        <p class="mb-0"><?php echo $major['salary_potential']; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($major['duration'])): ?>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong><i class="fas fa-clock me-2"></i>Duration:</strong></p>
                                        <p class="mb-0"><?php echo $major['duration']; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h4>Major Description</h4>
                        <div class="major-description">
                            <?php echo $major['description']; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($major['curriculum'])): ?>
                        <div class="mb-4">
                            <h4>Curriculum</h4>
                            <div class="major-curriculum">
                                <?php echo $major['curriculum']; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($major['career_prospects'])): ?>
                        <div class="mb-4">
                            <h4>Career Prospects</h4>
                            <div class="major-career-prospects">
                                <?php echo $major['career_prospects']; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Request Information</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?page=major-information-request" method="post">
                        <input type="hidden" name="major_id" value="<?php echo $major_id; ?>">
                        <input type="hidden" name="major_title" value="<?php echo $major['title']; ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Questions or Comments</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100">Request Information</button>
                    </form>
                </div>
            </div>
            
            <?php if (mysqli_num_rows($related_result) > 0): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Similar Majors</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php while ($related_major = mysqli_fetch_assoc($related_result)): ?>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($related_major['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $related_major['image'])): ?>
                                            <img src="<?php echo $related_major['image']; ?>" alt="<?php echo $related_major['title']; ?>" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light text-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-graduation-cap fa-lg text-secondary" style="line-height: 50px;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?php echo $related_major['title']; ?></h6>
                                            <small class="text-muted"><?php echo $related_major['university']; ?></small>
                                        </div>
                                    </div>
                                    <a href="index.php?page=explore/major-detail&id=<?php echo $related_major['id']; ?>" class="btn btn-sm btn-outline-danger mt-2">View Major</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
