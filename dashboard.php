<?php
require_once "includes/session.php";
cekLogin();
require_once "config/database.php";
include "includes/header.php";
?>

<h1>Halo, <?php echo htmlspecialchars($_SESSION["nama"]); ?> 👋</h1>
<p>Selamat datang kembali di E-Learning. Berikut ringkasan progres belajarmu.</p>

<div class="card-grid">
    <div class="card">
        <h3>📖 Materi Tersedia</h3>
        <?php
        $result = $koneksi->query("SELECT COUNT(*) as total FROM materi");
        $row = $result->fetch_assoc();
        echo "<p class='card-number'>" . $row["total"] . "</p>";
        ?>
    </div>

    <div class="card">
        <h3>📝 Kuis Selesai</h3>
        <?php
        $user_id = $_SESSION["user_id"];
        $stmt = $koneksi->prepare("SELECT COUNT(*) as total FROM nilai WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        echo "<p class='card-number'>" . $row["total"] . "</p>";
        ?>
    </div>

    <div class="card">
        <h3>🤖 Tanya AI</h3>
        <p>Punya pertanyaan seputar materi? Coba fitur chat AI di halaman materi.</p>
    </div>
</div>

<?php include "includes/footer.php"; ?>
