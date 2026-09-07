<?php
require_once "includes/session.php";
cekLogin();
require_once "config/database.php";
include "includes/header.php";

$materi_id = isset($_GET["materi_id"]) ? (int)$_GET["materi_id"] : null;
$hasil = null;

// Proses jawaban kuis yang dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jawaban_user = $_POST["jawaban"]; // array [soal_id => 'a'/'b'/'c'/'d']
    $skor = 0;
    $total = count($jawaban_user);

    foreach ($jawaban_user as $soal_id => $jawaban) {
        $stmt = $koneksi->prepare("SELECT jawaban_benar FROM kuis WHERE id = ?");
        $stmt->bind_param("i", $soal_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row && $row["jawaban_benar"] === $jawaban) {
            $skor++;
        }
    }

    $nilai_akhir = $total > 0 ? round(($skor / $total) * 100) : 0;

    // Simpan nilai ke database
    $user_id = $_SESSION["user_id"];
    $stmt = $koneksi->prepare("INSERT INTO nilai (user_id, materi_id, skor) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $materi_id, $nilai_akhir);
    $stmt->execute();

    $hasil = "Kamu benar $skor dari $total soal. Nilai: $nilai_akhir";
}
?>

<h1>📝 Kuis</h1>

<?php if ($hasil): ?>
    <div class="alert-success"><?php echo $hasil; ?></div>
    <a href="materi.php" class="btn">Kembali ke Materi</a>
<?php elseif ($materi_id): ?>
    <form method="POST" action="kuis.php?materi_id=<?php echo $materi_id; ?>">
        <?php
        $stmt = $koneksi->prepare("SELECT * FROM kuis WHERE materi_id = ?");
        $stmt->bind_param("i", $materi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $nomor = 1;
        while ($soal = $result->fetch_assoc()):
        ?>
            <div class="soal-box">
                <p><strong><?php echo $nomor++; ?>. <?php echo htmlspecialchars($soal["pertanyaan"]); ?></strong></p>
                <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="a" required> <?php echo htmlspecialchars($soal["pilihan_a"]); ?></label>
                <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="b"> <?php echo htmlspecialchars($soal["pilihan_b"]); ?></label>
                <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="c"> <?php echo htmlspecialchars($soal["pilihan_c"]); ?></label>
                <label><input type="radio" name="jawaban[<?php echo $soal['id']; ?>]" value="d"> <?php echo htmlspecialchars($soal["pilihan_d"]); ?></label>
            </div>
        <?php endwhile; ?>

        <button type="submit" class="btn">Kumpulkan Jawaban</button>
    </form>
<?php else: ?>
    <p>Pilih materi dulu untuk mengerjakan kuisnya.</p>
    <a href="materi.php" class="btn">Lihat Materi</a>
<?php endif; ?>

<?php include "includes/footer.php"; ?>
