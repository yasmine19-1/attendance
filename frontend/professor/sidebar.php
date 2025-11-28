<aside class="sidebar">
  <h3>Professor Portal</h3>
  <p class="muted">Welcome, Dr. Johnson</p>

  <nav class="prof-nav">
    <a href="index.php">Dashboard</a>
    <a href="../../backend/logout.php" class="logout">Logout</a>

  </nav>
</aside>

<style>
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 240px;
  height: 100vh;
  padding: 28px 20px;
  background: #ffffff;
  box-shadow: 1px 0 0 #eee;
  box-sizing: border-box;
  z-index: 10;
}

.sidebar h3 {
  margin: 0 0 6px 0;
  font-size: 18px;
}

.sidebar .muted {
  margin: 0 0 18px 0;
  color: #666;
  font-size: 14px;
}

.prof-nav a {
  display: block;
  padding: 10px 0;
  color: #0b5cff;
  text-decoration: none;
  font-size: 15px;
  font-weight: 500;
}

.prof-nav a.logout {
  color: #d00;
  margin-top: 18px;
}
</style>
