@extends('layouts.admin')
@section('page-title', 'Pengaturan')
@section('content')
<div class="page-header">
    <div>
        <h1>Pengaturan</h1>
        <p>Konfigurasi jam pelajaran (JP), profil sekolah, dan slide banner utama</p>
    </div>
</div>

<div class="settings-tabs" style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 1px solid var(--border-light); padding-bottom: 12px;">
    <a href="{{ route('admin.lesson-settings.edit') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-clock"></i> Jam Pelajaran (JP)
    </a>
    <a href="{{ route('admin.school-profile.edit') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-school"></i> Profil & Slide Banner
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.school-profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group full-width">
                    <label class="form-label">Nama Sekolah <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name ?? 'SMAN 1 Ciruas') }}" required>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Visi</label>
                    <textarea name="vision" class="form-control" rows="3" placeholder="Visi sekolah...">{{ old('vision', $item->vision ?? '') }}</textarea>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Misi</label>
                    <textarea name="mission" class="form-control" rows="4" placeholder="Misi sekolah (gunakan baris baru untuk setiap poin)...">{{ old('mission', $item->mission ?? '') }}</textarea>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Sejarah</label>
                    <textarea name="history" class="form-control" rows="5" placeholder="Sejarah singkat sekolah...">{{ old('history', $item->history ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Telepon / Fax</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $item->phone ?? '') }}" placeholder="Contoh: (0254) 280287">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Sekolah</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $item->email ?? '') }}" placeholder="Contoh: info@sman1ciruas.sch.id">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Alamat Lengkap</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $item->address ?? '') }}" placeholder="Alamat jalan lengkap sekolah">
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Embed Google Maps Iframe</label>
                    <textarea name="google_maps_iframe" class="form-control" rows="3" placeholder="Masukkan tag <iframe> dari Google Maps share link...">{{ old('google_maps_iframe', $item->google_maps_iframe ?? '') }}</textarea>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border); margin: 30px 0;">
            
            <h3 style="margin-bottom: 8px; font-weight: 700; font-size: 16px;">Slide Foto Banner (Hero Section)</h3>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 20px; line-height: 1.6;">
                Unggah 3 hingga 5 foto slide untuk latar belakang portal informasi halaman depan. <br>
                <strong>Panduan Foto:</strong> Resolusi landscape disarankan <strong>1920x1080 pixel</strong> (atau minimal <strong>1080x1080 pixel</strong>) dengan rasio aspek seragam untuk tampilan terbaik. Maksimal ukuran file 2 MB per foto. Overlay transparan gelap akan otomatis diterapkan untuk menjaga keterbacaan teks.
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                @for($i = 1; $i <= 5; $i++)
                    @php $fieldName = "hero_image_$i"; @endphp
                    <div class="form-group">
                        <label class="form-label">Foto Slide {{ $i }}</label>
                        <div class="file-upload" style="min-height: 150px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p style="margin: 4px 0 0 0;">Pilih foto slide {{ $i }}</p>
                            <input type="file" name="hero_image_{{ $i }}" accept="image/*" onchange="previewFile(this, {{ $i }})">
                            <div class="file-preview" id="preview-container-{{ $i }}" style="margin-top: 10px;">
                                @if($item && $item->$fieldName)
                                    <img src="{{ asset('storage/' . $item->$fieldName) }}" alt="Preview Slide {{ $i }}" style="max-width: 100%; max-height: 100px; border-radius: var(--radius); object-fit: cover;">
                                    <div style="margin-top: 8px;">
                                        <label style="color: #ef4444; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; font-weight: 500;">
                                            <input type="checkbox" name="delete_hero_image_{{ $i }}" value="1"> Hapus foto
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Profil</button>
            </div>
        </form>

        <script>
        function previewFile(input, index) {
            const container = document.getElementById('preview-container-' + index);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    container.innerHTML = `
                        <img src="${e.target.result}" alt="Preview Slide ${index}" style="max-width: 100%; max-height: 100px; border-radius: var(--radius); object-fit: cover;">
                        <div style="margin-top: 8px; font-size: 11px; color: #f59e0b; font-weight: 600;">
                            <i class="fas fa-info-circle"></i> Belum disimpan
                        </div>
                    `;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        </script>
    </div>
</div>
@endsection
