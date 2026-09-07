<?php
require_once "includes/session.php";
cekLogin();
require_once "config/database.php";
include "includes/header.php";

$materi_id = isset($_GET["id"]) ? (int)$_GET["id"] : null;
?>

<h1>📖 Materi Pembelajaran</h1>

<?php if (!$materi_id): ?>
    <!-- Tampilkan daftar semua materi -->
    <div class="card-grid">
        <?php
        $result = $koneksi->query("SELECT * FROM materi ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()):
        ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($row["judul"]); ?></h3>
                <p><?php echo htmlspecialchars($row["deskripsi"]); ?></p>
                <a href="materi.php?id=<?php echo $row["id"]; ?>" class="btn">Buka Materi</a>
            </div>
        <?php endwhile; ?>
    </div>

<?php else: ?>
    <!-- Tampilkan detail 1 materi + chat AI -->
    <?php
    $stmt = $koneksi->prepare("SELECT * FROM materi WHERE id = ?");
    $stmt->bind_param("i", $materi_id);
    $stmt->execute();
    $materi = $stmt->get_result()->fetch_assoc();
    ?>

    <?php if ($materi): ?>
        <a href="materi.php" class="back-link">&larr; Kembali ke daftar materi</a>
        <h2><?php echo htmlspecialchars($materi["judul"]); ?></h2>
        <div class="materi-content">
            <?php echo nl2br(htmlspecialchars($materi["konten"])); ?>
        </div>

        <a href="kuis.php?materi_id=<?php echo $materi["id"]; ?>" class="btn">Kerjakan Kuis Materi Ini</a>

        <!-- Fitur Chat AI -->
        <div class="ai-chat-box">
            <h3>🤖 Tanya AI tentang materi ini</h3>
            <div id="chat-log"></div>
            <div class="chat-input-row">
                <input type="text" id="chat-input" placeholder="Tulis pertanyaanmu...">
                <button id="chat-send">Kirim</button>
            </div>
        </div>

        <script>
            // Kirim materi_id ke JS biar tau materi mana yang lagi dibuka
            const MATERI_ID = <?php echo $materi["id"]; ?>;
        </script>
        <script src="js/ai-chat.js"></script>
    <?php else: ?>
        <p>Materi tidak ditemukan.</p>
    <?php endif; ?>
<?php endif; ?>

<?php include "includes/footer.php"; ?>
