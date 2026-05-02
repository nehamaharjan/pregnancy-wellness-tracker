<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pregnancy Helper</title>
    <link rel="stylesheet" href="assets/css/style.css">
<style>


/* Zigzag Section */
.features{
        display: flex;
    flex-direction: column;
    gap: 20px;
          max-width: 90%;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 229, 212, 0.72);
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.multi-line-zigzag {
    display: flex;
    flex-direction: column;
    gap: 20px;
          max-width: 90%;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 229, 212, 0.72);
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            
}

.zigzag-line {
    display: flex;
    padding: 10px 20px;
    background-color: rgba(255, 229, 212, 0.88);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    width: 100%;
     align-items: center;
    gap: 40px;
    
}

/* Image container takes 40% */
.zigzag-image {
    flex: 0 0 40%;
    height: 300px;        /* all containers same height */
    display: flex;
    justify-content: center;  /* center image horizontally */
    align-items: center;      /* center image vertically */
      overflow: hidden;      
       opacity: 0; 
    transform: translateY(50px);
    transition: all 0.8s ease;
}

.zigzag-image img {
   max-height: 100%;   /* image fits container height */
    max-width: 100%;    /* prevents image from overflowing width */
    width: auto;        /* preserve aspect ratio */
    height: auto;       /* preserve aspect ratio */
    border-radius: 15px;
 
}

/* Text container takes 60% */
.zigzag-text {
    flex: 0 0 50%;
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.8s ease;
}

.zigzag-text p {
    font: arial;
    font-size: 20px;
    text-decoration: justify;
    line-height: 1.5;
    margin: 0;
}

/* Scroll animation */
.zigzag-line.show .zigzag-image {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 0.8s;
}
.zigzag-line.show .zigzag-text {
    opacity: 1;
    transform: translateY(0);
    transition-delay: 1.5s; /* text appears after 1 second */
}

/* Responsive for smaller screens */
@media (max-width: 900px) {
    .zigzag-line {
        flex-direction: column;
        text-align: center;
    }
    .zigzag-image {
        flex: 0 0 80%;
        margin-bottom: 20px;
    }
    .zigzag-text {
        flex: 0 0 100%;
    }
    .zigzag-line.show .zigzag-text {
        transition-delay: 0.5s;
    }
}</style>
</head>
<body>
<!-- Header -->
<header>
     <div class="logo-section">
        <a href="index.php" style="display:flex; align-items:center; gap:8px; text-decoration:none;">
            <img src="assets/images/logo.png" alt="PregPal Logo">
            <h1>PregPal</h1>
        </a>
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="#app-info" id="about-link">About</a>
        <a href="auth/register.php">Register</a>
        <a href="auth/login.php">Login</a>
    </nav>
</header>

<!-- Top Hero Section -->
<section class="hero">
      <div class="hero-inner">
    <div class="hero-content">
        <div class="hero-image">
            <img src="assets/images/belly.png" alt="Pregnancy Image">
        </div>
        <div class="hero-text">
            <h2>Pregnancy Is a Journey</h2>
            <p>Pregnancy is a beautiful and transformative journey where a new life begins to grow inside a woman’s body.
                It brings a mix of emotions — joy, anticipation, and sometimes challenges — as the body nurtures and prepares for the arrival of a baby. 
                Each moment, from the first flutter to the final kick, is a step toward the miracle of birth and the beginning of a new chapter in life.</p>
        </div>
    </div>
</div>
</section>
<!-- images -->
   <h2>Let's See what Pregpal have to offer</h2>
<section class="multi-line-zigzag">

    <div class="zigzag-line">
        <div class="zigzag-image"><img src="assets/images/logsymptom.png" alt="Tip 1"></div>
        <div class="zigzag-text"><p>You can easily log what you are feeling that day simply by clicking the "+" sign on bottom of the page.
            This helps you remember how you felt throughout your pregnancy journey.
        </p></div>
    </div>

    <div class="zigzag-line">
         <div class="zigzag-text"><p>You now donot need to wonder what does it mean when you feel the certain way. 
            Here you can see what might be happening to you and if it is something you should be concerned about.
         </p></div>
        <div class="zigzag-image"><img src="assets/images/seesymptoms.png" alt="Tip 2"></div>
       
    </div>

    <div class="zigzag-line">
        <div class="zigzag-image"><img src="assets/images/insight.png" alt="Tip 3"></div>
        <div class="zigzag-text"><p>Don't need to brows multiple websites, you can get insights on various topics based on what trimester you are on.

        </p></div>
    </div>

    <div class="zigzag-line">
        <div class="zigzag-text"><p>You can also easily track your symptoms and se how you felt throught your journey through these simple charts.
            Now you can easily see your history no need to memorize anything.
        </p></div>
        <div class="zigzag-image"><img src="assets/images/charts.png" alt="Tip 3"></div>
        
    </div>

    <div class="zigzag-line">
        <div class="zigzag-image"><img src="assets/images/discussion.png" alt="Tip 3"></div>
        <div class="zigzag-text"><p>Ever have question or have doubt? You can clea your doubts by asking other people who might be going through same 
            experience through this discussion board. Get genuine answers that google might not answer.
        </p></div>
    </div>

</section>



<!-- Bottom App Info Section -->
<section id="app-info" class="features">
    <h3>Welcome to PregPal — your trusted companion during pregnancy.</h3>
    <p>PregPal is a simple and supportive web app designed to help expecting mothers track symptoms, stay informed, 
        and feel confident throughout their pregnancy journey. From daily symptom logging and health 
        tips to checkup reminders and milestone tracking, PregPal is here to make every step smoother, safer, and more connected. 
        Because every mom deserves a pal on this beautiful journey.</p>
</section>

<!-- Footer -->
<!-- <footer>
    <div class="container">
        <hr>
        <p>&copy; 2025 PregPal</p>
    </div>
</footer> -->

<script>
    // --- Sequential reveal for zigzag lines ---
const lines = document.querySelectorAll('.zigzag-line');
let currentIndex = 0;

const lineObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if(entry.isIntersecting && lines[currentIndex] === entry.target) {
            entry.target.classList.add('show');
            currentIndex++;
            if(currentIndex < lines.length) {
                lineObserver.observe(lines[currentIndex]);
            }
            lineObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.2 });

if(lines.length > 0){
    lineObserver.observe(lines[0]);
}

// --- Reveal Features section ---
const featuresSection = document.querySelector('.features');

const featuresObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            featuresSection.classList.add('show');
        }
    });
}, { threshold: 0.2 });

featuresObserver.observe(featuresSection);



    // Scroll to "About" section on click (optional since smooth-behavior is on)
    document.getElementById('about-link').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('app-info').scrollIntoView({ behavior: 'smooth' });
    });



</script>
</body>
</html>
