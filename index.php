<?php
    include 'layout/header.php';
?>

<div class="d-flex justify-content-center align-items-center mt-4">
    <h1 class="text-center fw-bold mb-4" style="color: white; font-size: 4rem;">Welcome to VetCare</h1>
</div>

<div class="container my-4">
  <div id="vetcareCarousel" class="carousel slide mt-3 rounded mx-auto shadow" style="max-width: 1200px;" data-bs-ride="carousel">
      <div class="carousel-inner rounded-4 overflow-hidden" style="height: 400px;">
        <div class="carousel-item active">
          <img src="layout/Asset/1banner.png" class="d-block w-100 h-100" style="object-fit: cover;">
        </div>
        <div class="carousel-item">
          <img src="layout/Asset/2banner.png" class="d-block w-100 h-100" style="object-fit: cover;">
        </div>
        <div class="carousel-item">
          <img src="layout/Asset/3banner.png" class="d-block w-100 h-100" style="object-fit: cover;">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#vetcareCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#vetcareCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
  </div>
</div>

<section class="container my-5">
  <div class="text-center px-3 px-md-5">
    <h2 class="fw-bold mb-4" style="color: orange;">About Us</h2>
    <p class="mx-auto text-secondary" style="max-width: 1000px; font-size: 1.1rem; line-height: 1.6;">
      The <strong>Veterinary Management System</strong> is a user-friendly platform designed to simplify veterinary clinic operations.
      It helps manage pet records, treatments, appointments, and user accounts with secure, role-based access for Super-Admins, Admins, and Pet Owners.
      Our goal is to make pet care more organized, efficient, and accessible.
    </p>
  </div>
</section>

<footer class="bg-light text-center text-muted py-4" style="font-size: 0.9rem; border-top: 1px solid #ddd;">
  <div class="container px-3 px-md-5">
    <p class="mb-1">&copy; 2025 Group 1. All rights reserved.</p>
    <p class="mb-1">
      This Veterinary Management System is a student project developed exclusively by Group 1 for
      <strong style="color: orange;">INTECH 2201: Web Applications Development, Second Term</strong>.
    </p>
    <p class="mb-1">This particular version of the system is intended for use by Group 1 only and is not for public distribution or use.</p>
    <p class="mb-0">This system is provided &quot;as is&quot; without warranty of any kind.</p>
  </div>
</footer>

<?php include 'layout/footer.php'; ?>