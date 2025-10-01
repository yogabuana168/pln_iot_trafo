### Panduan Push ke GitHub dengan Personal Access Token (PAT)

Ikuti langkah aman berikut untuk push ke GitHub tanpa menaruh token di repo atau history:

---

### 1) Prasyarat
- Repo GitHub: `https://github.com/yogabuana168/pln_iot_trafo.git`
- PAT dengan izin minimal: `repo` (atau Fine-grained: Contents Read/Write, Metadata Read)

---

### 2) Cek remote
```bash
git remote -v
```

---

### 3) Gunakan environment variable untuk token (JANGAN tulis token di file)
```bash
export GITHUB_TOKEN="<ISI_TOKEN_PUNYA_ANDA>"
```

Set remote `github` memakai format aman `x-access-token`:
```bash
git remote add github https://x-access-token:$GITHUB_TOKEN@github.com/yogabuana168/pln_iot_trafo.git 2>/dev/null || \
git remote set-url github https://x-access-token:$GITHUB_TOKEN@github.com/yogabuana168/pln_iot_trafo.git
```

---

### 4) Push branch
```bash
git push github main
```

---

### 5) Bersihkan environment variable
```bash
unset GITHUB_TOKEN
```

---

### 6) Opsi alternatif (credential helper)
```bash
git config --global credential.helper store
printf "protocol=https\nhost=github.com\nusername=x-access-token\npassword=%s\n\n" "<ISI_TOKEN_PUNYA_ANDA>" | git credential approve
git remote add github https://github.com/yogabuana168/pln_iot_trafo.git 2>/dev/null || git remote set-url github https://github.com/yogabuana168/pln_iot_trafo.git
git push github main
```

---

### Troubleshooting
- Push ditolak: "Push cannot contain secrets" → pastikan token tidak tertulis di file/commit.
- Permission denied → cek scope token dan repo access.
- Remote sudah ada → gunakan `git remote set-url`.

### Panduan Push ke GitHub dengan Personal Access Token (PAT)

Dokumen ini menjelaskan langkah ringkas dan aman untuk melakukan push repository ini ke GitHub menggunakan Personal Access Token (PAT), tanpa menyimpan token di dalam repository atau riwayat commit.

---

### 1) Prasyarat
- **URL repo GitHub**: mis. `https://github.com/yogabuana168/pln_iot_trafo.git`
- **Personal Access Token (PAT)** dengan scope minimal: `repo`.
- Akses shell pada server/proyek ini.

Catatan keamanan: JANGAN pernah commit token ke repository. Hindari menulis token langsung di command history; gunakan environment variable atau credential helper.

---

### 2) Cek remote git yang ada
```bash
git remote -v
```
Contoh output saat ini (server):
```
origin  /home/gpscente/repositories/pln_iot_trafo (fetch)
origin  /home/gpscente/repositories/pln_iot_trafo (push)
```

---

### 3) Tambahkan remote `github` (AMAN, via ENV VAR)
1. Export token ke environment variable sementara (tidak tersimpan permanen):
```bash
export GITHUB_TOKEN="<ISI_TOKEN_PUNYA_ANDA>"
```
2. Tambahkan remote bernama `github` menggunakan format token di URL kredensial (gunakan env var agar token tidak masuk ke history):
```bash
git remote add github https://$GITHUB_TOKEN@github.com/yogabuana168/pln_iot_trafo.git
```
3. Verifikasi:
```bash
git remote -v
```

Jika remote `github` sudah pernah ada, ganti URL-nya:
```bash
git remote set-url github https://$GITHUB_TOKEN@github.com/yogabuana168/pln_iot_trafo.git
```

Setelah selesai push, Anda dapat menghapus variable dari sesi terminal:
```bash
unset GITHUB_TOKEN
```

---

### 4) Opsi alternatif: Simpan kredensial dengan `git credential`
Jika ingin tidak menaruh token di command:
```bash
git config --global credential.helper store
printf "protocol=https\nhost=github.com\nusername=%s\npassword=%s\n\n" "github-user" "<ISI_TOKEN_PUNYA_ANDA>" | git credential approve
git remote add github https://github.com/yogabuana168/pln_iot_trafo.git
```
Catatan: `store` menyimpan kredensial di `~/.git-credentials` (plain text). Gunakan hanya pada server yang Anda kontrol. Alternatif yang lebih aman: `cache` atau `manager` (jika tersedia di OS Anda).

---

### 5) Push ke GitHub
```bash
git push github main
```
Jika branch berbeda, ganti `main` dengan nama branch Anda.

---

### 6) Verifikasi di GitHub
- Buka repo: `https://github.com/yogabuana168/pln_iot_trafo`
- Cek tab `Code` → commit terbaru harus sama dengan yang ada di server.

---

### 7) Integrasi cPanel Git™ Version Control (opsional)
Jika server cPanel di-set untuk pull dari GitHub:
- Pastikan `Remote URL` pada menu Git™ menunjuk ke `https://github.com/yogabuana168/pln_iot_trafo.git`.
- Gunakan tombol `Update from Remote` atau `Deploy HEAD Commit` setelah push jika deployment tidak otomatis.

---

### 8) Troubleshooting
- **remote rejected / permission denied**: scope token kurang; pastikan `repo` diaktifkan. Atau URL token salah format.
- **fatal: remote github already exists**: gunakan `git remote set-url github ...`.
- **token bocor di history**: ganti token dari GitHub dan cabut yang lama.
- **cPanel tidak auto-deploy**: gunakan menu `Pull or Deploy` → `Deploy HEAD Commit`.

---

### 9) Praktik terbaik keamanan
- Jangan commit atau menaruh token pada file proyek.
- Gunakan environment variable saat eksekusi command.
- Rotasi token berkala dan cabut token yang tidak dipakai.

---

Dokumen ini cukup untuk diikuti langkah demi langkah agar dapat melakukan push dari server ke GitHub dengan aman dan konsisten.


