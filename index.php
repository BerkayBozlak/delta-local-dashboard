<?php
/**
 * DELTA – Local Development Dashboard
 * Developed by Berkay Bozlak
 * Open Source
 *
 * TR: Local projeleri listeleyen ve yeni sekmede açan dashboard.
 * EN: A dashboard that lists local projects and opens them in new tabs.
 */

$BASE_FOLDER = 'BERKAYPROJECT';
$PROJECT_PATH = "C:/xampp/htdocs/$BASE_FOLDER";

$projects = [];
if (is_dir($PROJECT_PATH)) {
    $projects = array_values(array_filter(scandir($PROJECT_PATH), function ($item) use ($PROJECT_PATH) {
        return is_dir("$PROJECT_PATH/$item") && !in_array($item, ['.', '..']);
    }));
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>DELTA | Local Development Dashboard</title>

<style>
:root {
  --bg: #0f172a;
  --panel: #020617;
  --border: #1e293b;
  --accent: #38bdf8;
  --text: #e5e7eb;
  --muted: #94a3b8;
}

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: system-ui, -apple-system, BlinkMacSystemFont;
  background: var(--bg);
  color: var(--text);
  overflow: hidden;
}

/* HEADER */
header {
  height: 56px;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: var(--panel);
  border-bottom: 1px solid var(--border);
}

header .title {
  font-weight: 600;
  font-size: 16px;
}

header .author {
  font-size: 13px;
  color: var(--muted);
}

/* LAYOUT */
.main {
  display: flex;
  height: calc(100vh - 56px);
}

/* SIDEBAR */
.sidebar {
  width: 320px;
  min-width: 240px;
  max-width: 420px;
  padding: 16px;
  background: var(--panel);
  border-right: 1px solid var(--border);
  overflow-y: auto;
  overflow-x: hidden;
}

/* DIVIDER */
.divider {
  width: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--panel);
  border-right: 1px solid var(--border);
  pointer-events: none;
}

.divider-handle {
  width: 4px;
  height: 44px;
  border-radius: 4px;
  background: var(--muted);
  cursor: col-resize;
  pointer-events: auto;
}

.divider-handle:hover {
  background: var(--accent);
}

/* SECTIONS */
.section {
  margin-bottom: 20px;
}

.section h3 {
  margin: 0 0 10px;
  font-size: 14px;
  color: var(--accent);
}

.search {
  width: 100%;
  padding: 8px;
  margin-bottom: 12px;
  border-radius: 6px;
  background: #020617;
  border: 1px solid var(--border);
  color: var(--text);
}

/* GRID */
.grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 10px;
}

.card {
  padding: 10px;
  background: #020617;
  border: 1px solid var(--border);
  border-radius: 6px;
  text-align: center;
  font-size: 13px;
  cursor: pointer;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.card:hover {
  border-color: var(--accent);
}

/* PREVIEW PLACEHOLDER */
.preview {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--muted);
  font-size: 15px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .sidebar, .divider { display: none; }
}
</style>
</head>

<body>

<header>
  <div class="title">🚀 DELTA – Local Development Dashboard</div>
  <div class="author">Developed by Berkay Bozlak · Open Source</div>
</header>

<div class="main">

  <aside class="sidebar" id="sidebar">

    <input class="search" placeholder="Proje ara..." oninput="filterProjects(this.value)">

    <div class="section">
      <h3>⚡ Kısayollar</h3>
      <div class="grid">
        <div class="card" onclick="openNewTab('/phpmyadmin')">phpMyAdmin</div>
        <div class="card" onclick="openNewTab('/dashboard')">XAMPP</div>
        <div class="card" onclick="openNewTab('/')">Localhost</div>
      </div>
    </div>

    <div class="section">
      <h3>📁 Projeler</h3>
      <div class="grid" id="projectGrid">
        <?php foreach ($projects as $project): ?>
          <div class="card project"
               data-name="<?= strtolower($project) ?>"
               onclick="openProject('<?= $project ?>')">
            <?= htmlspecialchars($project) ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </aside>

  <div class="divider">
    <div class="divider-handle" id="dividerHandle"></div>
  </div>

  <!-- SAĞ ALAN ARTIK SADECE BİLGİLENDİRME -->
  <section class="preview">
    👈 Bir proje seçtiğinde yeni sekmede açılacaktır
  </section>

</div>

<script>
/* SIDEBAR RESIZE */
const sidebar = document.getElementById('sidebar');
const handle = document.getElementById('dividerHandle');

let isDragging = false;
sidebar.style.width = localStorage.getItem('sidebarWidth') || '320px';

handle.addEventListener('mousedown', () => {
  isDragging = true;
  document.body.style.userSelect = 'none';
});

document.addEventListener('mouseup', () => {
  isDragging = false;
  document.body.style.userSelect = '';
});

document.addEventListener('mousemove', e => {
  if (!isDragging) return;

  const rect = sidebar.getBoundingClientRect();
  let w = e.clientX - rect.left;

  if (w < 240) w = 240;
  if (w > 420) w = 420;

  sidebar.style.width = w + 'px';
  localStorage.setItem('sidebarWidth', w + 'px');
});

/* NAVIGATION */
function openNewTab(url) {
  window.open(url, '_blank');
}

function openProject(name) {
  const url = `/<?= $BASE_FOLDER ?>/${name}`;
  window.open(url, '_blank');
}

/* SEARCH */
function filterProjects(q) {
  q = q.toLowerCase();
  document.querySelectorAll('.project').forEach(p => {
    p.style.display = p.dataset.name.includes(q) ? '' : 'none';
  });
}
</script>

</body>
</html>


