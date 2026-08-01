<?php
// Script untuk memperbaiki file permissions di cPanel (suPHP/CloudLinux)
echo "<h1>Memulai perbaikan permissions...</h1>";

function fixPermissions($dir) {
    if (!file_exists($dir)) {
        echo "<p style='color:red'>Folder $dir tidak ditemukan!</p>";
        return;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    $dirCount = 0;
    $fileCount = 0;

    foreach ($iterator as $item) {
        if ($item->isDir()) {
            chmod($item->getRealPath(), 0755);
            $dirCount++;
        } else {
            chmod($item->getRealPath(), 0644);
            $fileCount++;
        }
    }
    
    // Pastikan folder utama juga diubah
    chmod($dir, 0755);
    
    echo "<p style='color:green'>BERHASIL! Telah mengubah $dirCount folder menjadi 0755 dan $fileCount file menjadi 0644 di dalam folder $dir.</p>";
}

// Target folder vendor
$vendorPath = realpath(__DIR__ . '/../vendor');
if ($vendorPath) {
    fixPermissions($vendorPath);
} else {
    echo "<p style='color:red'>Gagal menemukan folder vendor. Pastikan struktur foldernya benar.</p>";
}

echo "<h2>Selesai! Silakan hapus file ini demi keamanan, lalu refresh website Anda.</h2>";
?>
