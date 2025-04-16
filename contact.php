<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Tender Wings Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    :root 
    {
      --primary: #4361ee;
      --primary-light: #edf0ff;
      --secondary: #3f37c9;
      --accent: #4895ef;
      --danger: #f72585;
      --success: #4cc9f0;
      --warning: #f8961e;
      --dark: #212529;
      --light: #f8f9fa;
      --gray: #6c757d;
      --light-gray: #e9ecef;
      --border-radius: 12px;
      --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body 
    {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: var(--dark);
      line-height: 1.6;
    }
    
    .sidebar 
    {
      width: 280px;
      height: 100vh;
      background: linear-gradient(135deg, var(--secondary), var(--primary));
      position: fixed;
      top: 0;
      left: -280px;
      padding: 2rem 1.5rem;
      color: white;
      box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
      transition: var(--transition);
      z-index: 1000;
      display: flex;
      flex-direction: column;
    }
    
    .sidebar.active 
    {
      left: 0;
    }
    
    .sidebar-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .sidebar-logo {
      width: 120px;
      height: auto;
      margin-bottom: 1rem;
    }
    
    .sidebar-title {
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    
    .sidebar-menu {
      flex-grow: 1;
    }
    
    .sidebar-link {
      display: flex;
      align-items: center;
      color: white;
      text-decoration: none;
      padding: 0.8rem 1rem;
      margin-bottom: 0.5rem;
      border-radius: var(--border-radius);
      transition: var(--transition);
    }
    
    .sidebar-link:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateX(5px);
    }
    
    .sidebar-link i {
      font-size: 1.1rem;
      margin-right: 1rem;
      width: 24px;
      text-align: center;
    }
    
    .sidebar-footer {
      padding-top: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
      font-size: 0.9rem;
      opacity: 0.8;
    }
    
    .sidebar-toggle {
      position: fixed;
      top: 20px;
      left: 20px;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      cursor: pointer;
      z-index: 1001;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: var(--transition);
    }
    
    .sidebar-toggle:hover {
      background: var(--secondary);
      transform: scale(1.1);
    }
    
    .contact-hero {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('wallp.jpeg') no-repeat center center/cover;
      height: 60vh;
      display: flex;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .hero-content {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
      padding: 0 1rem;
    }
    
    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }
    
    .contact-section 
    {
      padding: 5rem 0;
    }
    
    .section-title 
    {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 3rem;
      text-align: center;
      position: relative;
    }
    
    .section-title::after 
    {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--primary);
      border-radius: 2px;
    }
    
    .contact-card 
    {
      background: white;
      border-radius: var(--border-radius);
      padding: 2rem;
      box-shadow: var(--box-shadow);
      height: 100%;
      transition: var(--transition);
    }
    
    .contact-card:hover 
    {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .contact-icon {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 1.5rem;
    }
    
    .form-control 
    {
      padding: 0.8rem 1rem;
      border-radius: var(--border-radius);
      border: 1px solid var(--light-gray);
      margin-bottom: 1.5rem;
      transition: var(--transition);
    }
    
    .form-control:focus 
    {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
      outline: none;
    }
    
    .btn-primary 
    {
      background: var(--primary);
      border: none;
      padding: 0.8rem 2rem;
      font-size: 1rem;
      font-weight: 500;
      border-radius: var(--border-radius);
      transition: var(--transition);
    }
    
    .btn-primary:hover 
    {
      background: var(--secondary);
      transform: translateY(-3px);
    }
    
    .contact-info {
      margin-bottom: 2rem;
    }
    
    .contact-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.5rem;
    }
    
    .contact-item i {
      font-size: 1.2rem;
      color: var(--primary);
      margin-right: 1rem;
      margin-top: 0.2rem;
    }
    
    .social-links {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
    }
    
    .social-link 
    {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--primary);
      color: white;
      transition: var(--transition);
    }
    
    .social-link:hover 
    {
      background: var(--secondary);
      transform: translateY(-3px);
    }
    
    .map-container 
    {
      height: 400px;
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--box-shadow);
      margin-top: 3rem;
    }
    
    .map-container iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    .footer 
    {
      background: var(--dark);
      color: white;
      padding: 3rem 0 1rem;
    }
    
    .footer-logo 
    {
      width: 150px;
      margin-bottom: 1.5rem;
    }
    
    .footer-about 
    {
      margin-bottom: 1.5rem;
    }
    
    .footer-title 
    {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .footer-title::after 
    {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 40px;
      height: 3px;
      background: var(--primary);
    }
    
    .footer-links 
    {
      list-style: none;
      padding: 0;
    }
    
    .footer-links li 
    {
      margin-bottom: 0.8rem;
    }
    
    .footer-links a 
    {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: var(--transition);
      display: inline-block;
    }
    
    .footer-links a:hover 
    {
      color: white;
      transform: translateX(5px);
    }
    
    .footer-bottom 
    {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 1.5rem;
      margin-top: 2rem;
      text-align: center;
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.5);
    }
    
    @media (max-width: 992px) 
    {
      .hero-title {
        font-size: 2.5rem;
      }
      
      .section-title {
        font-size: 2.2rem;
      }
    }
    
    @media (max-width: 768px) 
    {
      .hero-title 
      {
        font-size: 2rem;
      }
      
      .section-title 
      {
        font-size: 2rem;
      }
      
      .sidebar 
      {
        width: 250px;
      }
    }
    
    @media (max-width: 576px) 
    {
      .hero-title {
        font-size: 1.8rem;
      }
      
      .contact-section {
        padding: 3rem 0;
      }
      
      .section-title {
        font-size: 1.8rem;
        margin-bottom: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <img src="logotw.png" alt="Tender Wings Home Logo" class="sidebar-logo">
      <div class="sidebar-title">Tender Wings Home</div>
    </div>
    
    <div class="sidebar-menu">
      <a href="index.php" class="sidebar-link">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
      <a href="#contact" class="sidebar-link">
        <i class="fas fa-envelope"></i>
        <span>Contact Us</span>
      </a>
    </div>
    
    <div class="sidebar-footer">
      <p>© 2025 Tender Wings Home</p>
      <p>Bringing Hope to Children</p>
    </div>
  </div>

  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>

  <section class="contact-hero">
    <div class="hero-content">
      <h1 class="hero-title">Shaping Smiles, Inspiring Dreams, Changing Lives</h1>
    </div>
  </section>

  <section id="contact" class="contact-section">
    <div class="container">
      <h2 class="section-title">Get In Touch</h2>
      <p class="text-center mb-5">We'd love to hear from you. Reach out for inquiries, partnerships, or support.</p>
      
      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="contact-card">
            <i class="fas fa-envelope contact-icon"></i>
            <h3>Send Us a Message</h3>
            
            <form>
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Your Name" required>
              </div>
              
              <div class="form-group">
                <input type="email" class="form-control" placeholder="Your Email" required>
              </div>
              
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Subject">
              </div>
              
              <div class="form-group">
                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
              </div>
              
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Send Message
              </button>
            </form>
          </div>
        </div>
        
        <div class="col-lg-6 mb-4">
          <div class="contact-card">
            <i class="fas fa-map-marker-alt contact-icon"></i>
            <h3>Contact Information</h3>
            
            <div class="contact-info">
              <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                  <h5>Address</h5>
                  <p>Hope Street, Kiambu, Kenya</p>
                </div>
              </div>
              
              <div class="contact-item">
                <i class="fas fa-phone-alt"></i>
                <div>
                  <h5>Phone</h5>
                  <p>+254 729 298 735</p>
                </div>
              </div>
              
              <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                  <h5>Email</h5>
                  <p>info@tenderwingshome.org</p>
                </div>
              </div>
            </div>
            
            <h4>Follow Us</h4>
            <div class="social-links">
              <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
              <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
              <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
              <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Map Section -->
      <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.854315345093!2d36.82121431475392!3d-1.265735535980028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f173c0a1f9de7%3A0x1e3e0e1b4e8b8b8b!2sHope%20Street%2C%20Kiambu!5e0!3m2!1sen!2ske!4v1620000000000!5m2!1sen!2ske" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <img src="logotw.png" alt="Tender Wings Home Logo" class="footer-logo">
          <div class="footer-about">
            <p>Tender Wings Home is a non-profit organization dedicated to providing shelter, education, and care to children in need.</p>
          </div>
        </div>
        
        <div class="col-lg-2 col-md-6 mb-4">
          <h3 class="footer-title">Quick Links</h3>
          <ul class="footer-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#contact">Contact Us</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <h3 class="footer-title">Legal</h3>
          <ul class="footer-links">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Financial Reports</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
          <h3 class="footer-title">Contact</h3>
          <ul class="footer-links">
            <li><i class="fas fa-map-marker-alt"></i> Hope Street, Kiambu</li>
            <li><i class="fas fa-phone-alt"></i> +254 729 298 735</li>
            <li><i class="fas fa-envelope"></i> info@tenderwingshome.org</li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; 2025 Tender Wings Home. All rights reserved. Registered Charity No. 123456</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
 
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    sidebarToggle.addEventListener('click', () => 
    {
      sidebar.classList.toggle('active');
    });
    

    document.addEventListener('click', (event) => 
    {
      if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) 
      {
        sidebar.classList.remove('active');
      }
    });
    

    document.querySelectorAll('a[href^="#"]').forEach(anchor => 
    {
      anchor.addEventListener('click', function(e) 
      {
        e.preventDefault();
        
        sidebar.classList.remove('active');
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 80,
            behavior: 'smooth'
          });
        }
      });
    });
    
    document.querySelector('form').addEventListener('submit', function(e) 
    {
      const name = this.querySelector('input[type="text"]').value.trim();
      const email = this.querySelector('input[type="email"]').value.trim();
      const message = this.querySelector('textarea').value.trim();
      
      if (!name || !email || !message) 
      {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return;
      }
      
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) 
      {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
      }
      
      const submitBtn = this.querySelector('button[type="submit"]');
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
      submitBtn.disabled = true;
    });
  </script>
</body>
</html>