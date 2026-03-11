</div> <!-- container end -->

<!-- ===== FOOTER ===== -->
<footer class="footer mt-5">

  <!-- stylish divider -->
  <div class="footer-divider"></div>

  <div class="container text-center py-4 small text-muted">

    <div class="mb-1">
      © <?= date('Y') ?> <strong>Mulund College of Commerce (MCC)</strong>
    </div>

    <div class="mb-1">
      Department of Commerce – Event Management System
    </div>

    <div>
      Designed & Developed by <b>Samiksha Mahadik</b>
    </div>

  </div>

</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ================= DARK MODE ================= -->
<script>
const toggle = document.getElementById('darkToggle');
const body = document.body;

// Load saved mode
if (localStorage.getItem('dark') === 'yes') {
  body.classList.add('dark');
}

// Toggle mode
if (toggle) {
  toggle.addEventListener('click', () => {
    body.classList.toggle('dark');
    localStorage.setItem(
      'dark',
      body.classList.contains('dark') ? 'yes' : 'no'
    );
  });
}
</script>

<!-- ================= LIVE CLOCK ================= -->
<script>
setInterval(() => {
  const clock = document.getElementById('liveClock');
  if(clock){
    const now = new Date();
    clock.innerHTML = now.toLocaleTimeString();
  }
}, 1000);
</script>

<!-- ================= DASHBOARD COUNT ANIMATION ================= -->
<script>
document.querySelectorAll('.stat-card h4').forEach(el => {
  const target = parseInt(el.innerText);
  if (isNaN(target)) return;

  let count = 0;
  const step = Math.max(1, Math.floor(target / 40));

  const interval = setInterval(() => {
    count += step;
    if (count >= target) {
      el.innerText = target;
      clearInterval(interval);
    } else {
      el.innerText = count;
    }
  }, 20);
});
</script>

<!-- ================= EVENT CATEGORY IMAGE AUTO LOAD ================= -->
<script>
const categoryImages = {
  technical: "assets/events/tech.jpg",
  cultural: "assets/events/cultural.jpg",
  sports: "assets/events/sports.jpg",
  workshop: "assets/events/workshop.jpg"
};

document.querySelectorAll('.event-img').forEach(img => {
  const cat = img.dataset.category;
  if (cat && categoryImages[cat]) {
    img.src = categoryImages[cat];
  }
});
</script>

</body>
</html>
