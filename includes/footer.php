<?php
// includes/footer.php
?>
  </main>

  <!-- OS Taskbar / Explorer Footer -->
  <footer class="taskbar-footer">
    <div class="container taskbar-container">
      
      <!-- System / User Status -->
      <div class="taskbar-status">
        <span class="status-indicator online" title="System Online"></span>
        <span class="taskbar-brand">Sean John A. Duque</span>
        <span class="taskbar-divider">|</span>
        <span class="taskbar-role">IT Professional & Web Developer</span>
      </div>

      <!-- Quick Launch / Social Links -->
      <div class="taskbar-links">
        <a href="https://github.com/seanduque" target="_blank" rel="noopener noreferrer" class="taskbar-item">
          <span class="icon">💻</span> GitHub
        </a>
        <a href="https://linkedin.com/in/seanduque" target="_blank" rel="noopener noreferrer" class="taskbar-item">
          <span class="icon">💼</span> LinkedIn
        </a>
        <a href="mailto:seanjohn.duque@gmail.com" class="taskbar-item">
          <span class="icon">✉️</span> Email
        </a>
      </div>

      <!-- System Clock / Copyright & Return Controls -->
      <div class="taskbar-system">
        <span class="copyright">&copy; <?php echo date('Y'); ?> All rights reserved.</span>
        <a href="#home" class="back-to-top-btn" title="Return to Top (Home)">
          <span>▲ Top</span>
        </a>
      </div>

    </div>
  </footer>

  <!-- Scripts -->
  <script src="assets/js/main.js"></script>

  <!-- File Explorer Image Viewer Modal -->
  <div id="image-viewer-modal" class="modal-overlay">
    <div class="explorer-window modal-window">
      <!-- Titlebar -->
      <div class="window-titlebar">
        <div class="window-title">
          <span class="icon">🖼️</span>
          <span id="modal-filename">Photos — preview.png</span>
        </div>
        <div class="window-controls">
          <span class="win-btn minimize"></span>
          <span class="win-btn maximize"></span>
          <span class="win-btn close" id="modal-close-btn"></span>
        </div>
      </div>

      <!-- Address / Path Bar -->
      <div class="window-addressbar">
        <span>File Location:</span>
        <div class="address-path" id="modal-filepath">C:\Portfolio\Projects\preview.png</div>
      </div>

      <!-- Modal Body -->
      <div class="modal-body grid-2">
        <div class="modal-image-container">
          <img id="modal-img" src="" alt="Project Full Image">
        </div>

        <div class="modal-details">
          <span class="project-tag" id="modal-category">Category</span>
          <h2 id="modal-title">Project Title</h2>
          <p id="modal-summary">Project Summary</p>
          
          <div class="modal-tech-stack">
            <strong>Technologies Used:</strong>
            <p id="modal-tech">PHP, MySQL</p>
          </div>

          <div class="modal-actions">
            <a href="#" id="modal-github" target="_blank" class="btn-terminal secondary">
              <span class="icon">💻</span> View Repository
            </a>
            <a href="#" id="modal-demo" target="_blank" class="btn-terminal primary">
              <span class="icon">🌐</span> Live Demo
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- File Explorer Certificate Viewer Modal -->
  <div id="cert-viewer-modal" class="modal-overlay">
    <div class="explorer-window modal-window">
      <!-- Titlebar -->
      <div class="window-titlebar">
        <div class="window-title">
          <span class="icon">📜</span>
          <span id="cert-modal-filename">Certificate — preview.pfx</span>
        </div>
        <div class="window-controls">
          <span class="win-btn minimize"></span>
          <span class="win-btn maximize"></span>
          <span class="win-btn close" id="cert-modal-close-btn"></span>
        </div>
      </div>

      <!-- Address Bar -->
      <div class="window-addressbar">
        <span>File Location:</span>
        <div class="address-path" id="cert-modal-filepath">C:\Portfolio\Certifications\preview.pfx</div>
      </div>

      <!-- Modal Body -->
      <div class="modal-body grid-2">
        <div class="modal-image-container">
          <img id="cert-modal-img" src="" alt="Certificate Full Image">
        </div>

        <div class="modal-details">
          <span class="project-tag" id="cert-modal-date" style="background: rgba(16, 185, 129, 0.15); color: #10B981; border-color: #10B981;">Issued Date</span>
          <h2 id="cert-modal-title">Certificate Title</h2>
          <h4 id="cert-modal-issuer" style="color: #94A3B8; font-weight: normal;">Issuing Organization</h4>
          
          <div class="modal-tech-stack" style="margin-top: 0.5rem;">
            <strong>Credential Info:</strong>
            <p id="cert-modal-id" style="color: #3B82F6;">ID: N/A</p>
          </div>

          <div class="modal-actions" style="margin-top: 1rem;">
            <a href="#" id="cert-modal-url" target="_blank" class="btn-terminal primary">
              <span class="icon">↗</span> Verify Credential Online
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  
</body>
</html>