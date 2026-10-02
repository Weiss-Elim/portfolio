document.addEventListener('DOMContentLoaded', () => {
  const navToggle = document.getElementById('nav-toggle');
  const navLinks = document.getElementById('nav-links');

  // Mobile Menu Toggle
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
  }

  // Smooth Scrolling for Internal Links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
        if (navLinks.classList.contains('active')) {
          navLinks.classList.remove('active');
        }
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const contactForm = document.getElementById('contact-form');
  const formStatus = document.getElementById('form-status');

  if (contactForm) {
    contactForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      const submitBtn = contactForm.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending...';
      formStatus.textContent = '';
      formStatus.className = 'form-status-msg';

      const formData = new FormData(contactForm);

      try {
        const response = await fetch('api/contact.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        if (response.ok && data.status === 'success') {
          formStatus.textContent = data.message;
          formStatus.classList.add('status-success');
          contactForm.reset();
        } else {
          // Display validation or server error
          const errorMsg = data.message || Object.values(data.errors || {}).join(' ');
          formStatus.textContent = errorMsg;
          formStatus.classList.add('status-error');
        }
      } catch (err) {
        formStatus.textContent = 'Network error. Please try again later.';
        formStatus.classList.add('status-error');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message';
      }
    });
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('image-viewer-modal');
  const closeBtn = document.getElementById('modal-close-btn');

  // FIX: Explicitly target project file items only (excluding cert items)
  const projectFileItems = document.querySelectorAll('.file-item:not(.cert-item)');

  // Modal elements to populate
  const modalImg = document.getElementById('modal-img');
  const modalFilename = document.getElementById('modal-filename');
  const modalFilepath = document.getElementById('modal-filepath');
  const modalTitle = document.getElementById('modal-title');
  const modalCategory = document.getElementById('modal-category');
  const modalSummary = document.getElementById('modal-summary');
  const modalTech = document.getElementById('modal-tech');
  const modalGithub = document.getElementById('modal-github');
  const modalDemo = document.getElementById('modal-demo');

  // Open Projects Modal on project file click
  projectFileItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.stopPropagation(); // Stop event bubbling

      const title = item.getAttribute('data-title');
      const category = item.getAttribute('data-category');
      const summary = item.getAttribute('data-summary');
      const tech = item.getAttribute('data-tech');
      const imgSrc = item.getAttribute('data-img');
      const github = item.getAttribute('data-github');
      const demo = item.getAttribute('data-demo');

      const fileName = title.toLowerCase().replace(/[^a-z0-9]/g, '_') + '.png';

      modalImg.src = imgSrc;
      modalFilename.textContent = `Photos — ${fileName}`;
      modalFilepath.textContent = `C:\\Portfolio\\Projects\\${fileName}`;
      modalTitle.textContent = title;
      modalCategory.textContent = category;
      modalSummary.textContent = summary;
      modalTech.textContent = tech;

      if (github && github.trim() !== '') {
        modalGithub.href = github;
        modalGithub.style.display = 'inline-flex';
      } else {
        modalGithub.style.display = 'none';
      }

      if (demo && demo.trim() !== '') {
        modalDemo.href = demo;
        modalDemo.style.display = 'inline-flex';
      } else {
        modalDemo.style.display = 'none';
      }

      modal.classList.add('active');
    });
  });

  const closeModal = () => modal.classList.remove('active');
  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
});

// Separate Certificate Viewer Modal Handler
document.addEventListener('DOMContentLoaded', () => {
  const certModal = document.getElementById('cert-viewer-modal');
  const certCloseBtn = document.getElementById('cert-modal-close-btn');
  const certItems = document.querySelectorAll('.cert-item');

  const certImg = document.getElementById('cert-modal-img');
  const certFilename = document.getElementById('cert-modal-filename');
  const certFilepath = document.getElementById('cert-modal-filepath');
  const certTitle = document.getElementById('cert-modal-title');
  const certIssuer = document.getElementById('cert-modal-issuer');
  const certDate = document.getElementById('cert-modal-date');
  const certId = document.getElementById('cert-modal-id');
  const certUrl = document.getElementById('cert-modal-url');

  certItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.stopPropagation(); // Stop event bubbling

      const title = item.getAttribute('data-title');
      const issuer = item.getAttribute('data-issuer');
      const date = item.getAttribute('data-date');
      const credId = item.getAttribute('data-id');
      const url = item.getAttribute('data-url');
      const imgSrc = item.getAttribute('data-img');

      const fileName = title.toLowerCase().replace(/[^a-z0-9]/g, '_') + '.pfx';

      certImg.src = imgSrc;
      certFilename.textContent = `Certificate — ${fileName}`;
      certFilepath.textContent = `C:\\Portfolio\\Certifications\\${fileName}`;
      certTitle.textContent = title;
      certIssuer.textContent = `Issued by: ${issuer}`;
      certDate.textContent = date;
      certId.textContent = `Credential ID: ${credId}`;

      if (url && url.trim() !== '') {
        certUrl.href = url;
        certUrl.style.display = 'inline-flex';
      } else {
        certUrl.style.display = 'none';
      }

      certModal.classList.add('active');
    });
  });

  const closeCertModal = () => certModal.classList.remove('active');

  if (certCloseBtn) certCloseBtn.addEventListener('click', closeCertModal);

  certModal.addEventListener('click', (e) => {
    if (e.target === certModal) closeCertModal();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (modal && modal.classList.contains('active')) closeModal();
      if (certModal && certModal.classList.contains('active')) closeCertModal();
    }
  });
});