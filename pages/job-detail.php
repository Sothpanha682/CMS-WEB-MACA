<?php
// Prevent direct access to this file
if (!defined('INCLUDED')) {
    header("HTTP/1.0 403 Forbidden");
    exit;
}

// Get job ID from URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get job details from database
$query = "SELECT * FROM popular_jobs WHERE id = $job_id";
$result = mysqli_query($conn, $query);

// Check if job exists
if (mysqli_num_rows($result) == 0) {
    echo '<div class="container my-5"><div class="alert alert-danger">Job not found!</div></div>';
    exit;
}

$job = mysqli_fetch_assoc($result);

// Get related jobs
$related_query = "SELECT id, title, company, image FROM popular_jobs WHERE id != $job_id ORDER BY RAND() LIMIT 3";
$related_result = mysqli_query($conn, $related_query);
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <?php if (!empty($job['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $job['image'])): ?>
                    <img src="<?php echo $job['image']; ?>" class="card-img-top" alt="<?php echo $job['title']; ?>" style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h1 class="card-title"><?php echo $job['title']; ?></h1>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger me-2">Job</span>
                        <span class="text-muted"><i class="fas fa-building me-1"></i> <?php echo $job['company']; ?></span>
                    </div>
                    
                    <?php if (!empty($job['salary'])): ?>
                        <div class="alert alert-light border mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="fas fa-money-bill-wave me-2"></i>Salary Range:</strong></p>
                                    <p class="mb-0"><?php echo $job['salary']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="fas fa-map-marker-alt me-2"></i>Location:</strong></p>
                                    <p class="mb-0"><?php echo !empty($job['location']) ? $job['location'] : 'Various Locations'; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h4>Job Description</h4>
                        <div class="job-description">
                            <?php echo $job['description']; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($job['requirements'])): ?>
                        <div class="mb-4">
                            <h4>Requirements</h4>
                            <div class="job-requirements">
                                <?php echo $job['requirements']; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($job['benefits'])): ?>
                        <div class="mb-4">
                            <h4>Benefits</h4>
                            <div class="job-benefits">
                                <?php echo $job['benefits']; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Apply for this Job</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?page=job-application" method="post">
                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                        <input type="hidden" name="job_title" value="<?php echo $job['title']; ?>">
                        
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
                            <label for="message" class="form-label">Cover Letter</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="resume" class="form-label">Resume/CV</label>
                            <input type="file" class="form-control" id="resume" name="resume">
                            <div class="form-text">Upload your resume (PDF, DOC, DOCX)</div>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100">Submit Application</button>
                    </form>
                </div>
            </div>
            
            <?php if (mysqli_num_rows($related_result) > 0): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Similar Jobs</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php while ($related_job = mysqli_fetch_assoc($related_result)): ?>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($related_job['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $related_job['image'])): ?>
                                            <img src="<?php echo $related_job['image']; ?>" alt="<?php echo $related_job['title']; ?>" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light text-center me-3" style="width: 50px; height: 50px;">
                                                <i class="fas fa-briefcase fa-lg text-secondary" style="line-height: 50px;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?php echo $related_job['title']; ?></h6>
                                            <small class="text-muted"><?php echo $related_job['company']; ?></small>
                                        </div>
                                    </div>
                                    <a href="index.php?page=explore/job-detail&id=<?php echo $related_job['id']; ?>" class="btn btn-sm btn-outline-danger mt-2">View Job</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
