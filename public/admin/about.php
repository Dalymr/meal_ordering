<?php
require '../../includes/auth_check.php';
require '../../config/db.php';

if (!$_SESSION['user']['is_admin']) {
    header('Location: ../index.php');
    exit;
}

include '../../includes/admin_header.php';
?>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-dark text-white">
        <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>À propos du Projet FoodFrenzy - Administration</h4>
      </div>
      <div class="card-body">
        <h5 class="border-bottom pb-2">Projet Universitaire - TEKUP Tunisia</h5>
        <p>
          Ce système d'administration a été développé dans le cadre d'un projet universitaire pour l'Université TEKUP en Tunisie
          durant l'année académique 2023/2024. Il fournit une interface complète pour gérer la plateforme FoodFrenzy,
          permettant de contrôler les utilisateurs, les repas, les commandes et d'accéder aux statistiques.
        </p>
        
        <h5 class="border-bottom pb-2 mt-4">Architecture Technique</h5>
        <div class="row mt-3">
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fab fa-php fa-2x text-primary"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Backend PHP</h6>
                <p class="text-muted">Architecture MVC simplifiée avec séparation des préoccupations et sécurité renforcée.</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fas fa-database fa-2x text-info"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Base de données MySQL</h6>
                <p class="text-muted">Conception relationnelle optimisée avec clés étrangères et indexation pour les performances.</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fas fa-shield-alt fa-2x text-danger"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Sécurité</h6>
                <p class="text-muted">Protection contre les injections SQL, XSS, CSRF et authentification à plusieurs niveaux.</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fas fa-chart-line fa-2x text-success"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Analytique</h6>
                <p class="text-muted">Tableaux de bord avec visualisations de données pour suivre les tendances et performances.</p>
              </div>
            </div>
          </div>
        </div>
        
        <h5 class="border-bottom pb-2 mt-4">Structure du Projet</h5>
        <pre class="bg-light p-3 mt-3" style="font-size: 0.85rem;">
wamp64/www/
├── config/         # Configuration BD et constants
├── includes/       # En-têtes, pied de pages, auth
├── public/         # Pages accessibles
│   ├── admin/      # Interfaces administration
│   └── ...         # Interfaces utilisateurs
├── uploads/        # Stockage des images
│   ├── meals/      # Images des repas
│   └── profiles/   # Photos de profil
└── css, js/        # Assets front-end
        </pre>
        
        <div class="alert alert-warning mt-4">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Note technique:</strong> Ce panneau d'administration est sécurisé et réservé aux utilisateurs autorisés.
          Toute tentative d'accès non autorisé sera enregistrée et signalée.
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Projet Académique</h5>
      </div>
      <div class="card-body text-center">
        <img src="/img/tekup-logo.png" alt="TEKUP" class="img-fluid mb-3" style="max-height: 80px;"
             onerror="this.src='https://tekup.eu/wp-content/uploads/2023/01/tekup.webp'; this.onerror=null;">
        <h5>TEKUP Tunisia</h5>
        <p class="text-muted">Développé dans le cadre du cours de Développement Web Avancé - 2023/2024.</p>
      </div>
    </div>
    
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-code me-2"></i>Technologies</h5>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <i class="fab fa-php fa-2x text-primary me-3"></i>
          <div>
            <h6 class="mb-0">PHP 8.x</h6>
            <div class="progress" style="height: 5px;">
              <div class="progress-bar" role="progressbar" style="width: 85%"></div>
            </div>
          </div>
        </div>
        
        <div class="d-flex align-items-center mb-3">
          <i class="fas fa-database fa-2x text-info me-3"></i>
          <div>
            <h6 class="mb-0">MySQL</h6>
            <div class="progress" style="height: 5px;">
              <div class="progress-bar bg-info" role="progressbar" style="width: 80%"></div>
            </div>
          </div>
        </div>
        
        <div class="d-flex align-items-center mb-3">
          <i class="fab fa-bootstrap fa-2x text-purple me-3"></i>
          <div>
            <h6 class="mb-0">Bootstrap 5</h6>
            <div class="progress" style="height: 5px;">
              <div class="progress-bar bg-purple" role="progressbar" style="width: 90%"></div>
            </div>
          </div>
        </div>
        
        <div class="d-flex align-items-center">
          <i class="fab fa-js fa-2x text-warning me-3"></i>
          <div>
            <h6 class="mb-0">JavaScript</h6>
            <div class="progress" style="height: 5px;">
              <div class="progress-bar bg-warning" role="progressbar" style="width: 75%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Soutien à la Palestine</h5>
      </div>
      <div class="card-body">
        <div class="text-center mb-3">
          <img src="/img/palestine-flag.png" alt="Palestine Flag" class="img-fluid" style="max-width: 100px;"
               onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/0/00/Flag_of_Palestine.svg'; this.onerror=null;">
        </div>
        <p>
          Nous soutenons le peuple palestinien dans sa lutte pour la liberté et la dignité. 
          Viva Palestina!
        </p>
        <div class="d-grid">
          <a href="https://www.palestinercs.org/en" target="_blank" class="btn btn-success">Faire un don</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
