<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\NotificationTemplate;
use App\Models\Transformator as TransformatorModel;
use App\Models\TypeTransformator;
use App\Models\WhatsappNotificationSetting;
use App\Models\Position;
use App\Models\Department;
use App\Models\UserGroup;

// Root -> login
Route::get('/', function () {
    return redirect('/login');
});

// Login (view only)
Route::get('/login', function () {
    return view('auth-signin');
})->name('login');

// Handle login (dummy session-based, no DB)
Route::post('/login', function (Request $request) {
    // Validate input
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string'
    ]);
    
    $username = $request->username;
    $password = $request->password;
    
    // Simple authentication (you can enhance this with database check)
    // For now, we'll accept any non-empty credentials and store user data
    if (!empty($username) && !empty($password)) {
        // Store user data in session
        $request->session()->put('logged_in', true);
        $request->session()->put('user', [
            'username' => $username,
            'name' => ucfirst($username), // Capitalize first letter
            'login_time' => now()
        ]);
        
        // Check if AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'username' => $username,
                    'name' => ucfirst($username)
                ],
                'redirect' => '/dashboard'
            ]);
        }
        
        return redirect('/dashboard');
    }
    
    // Login failed
    if ($request->expectsJson() || $request->ajax()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid username or password'
        ], 401);
    }
    
    return redirect('/login')->with('error', 'Invalid username or password');
});

// Dashboard (protected via simple session check)
Route::get('/dashboard', function (Request $request) {
    if (!$request->session()->get('logged_in')) {
        return redirect('/login');
    }
    return view('dashboard');
});

// Settings System (open for now, same layout as dashboard placeholder)
Route::get('/settings/system', function () {
    // Load site settings from database
    $settings = DB::table('site_settings')->pluck('setting_value', 'setting_key')->toArray();
    
    // Default values if not set
    $defaultSettings = [
        'site_name' => 'PLN GPS Center',
        'site_description' => 'PLN GPS Center Management System',
        'footer_text' => '© 2025 PLN GPS Center. Design & Develop by Pixeleyez',
        'default_theme' => 'light',
        'timezone' => 'Asia/Jakarta',
        'logo_path' => null,
        'logo_icon_path' => null,
        'favicon_path' => null,
        'logo_width' => '150',
        'logo_height' => '40',
        'favicon_size' => '32',
        // SMTP Settings
        'smtp_host' => '',
        'smtp_port' => '587',
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_encryption' => 'tls',
        'smtp_from_name' => 'PLN GPS Center',
        // Google Maps Settings
        'google_maps_api_key' => '',
        'default_latitude' => '-6.2088',
        'default_longitude' => '106.8456',
        'default_zoom' => '10',
        'default_map_type' => 'roadmap',
        // WhatsApp Settings
        'whatsapp_api_key' => '',
        'whatsapp_phone_number_id' => '',
        'whatsapp_business_account_id' => '',
        'whatsapp_webhook_url' => '',
        'whatsapp_webhook_verify_token' => '',
        'whatsapp_default_template' => 'Hello {{name}}, this is a notification from PLN GPS Center. Thank you for using our service.',
        'whatsapp_notification_status' => 'active',
        'whatsapp_test_phone' => '',
        // Firebase Settings
        'firebase_server_key' => '',
        'firebase_project_id' => '',
        'firebase_app_id' => '',
        'firebase_config_json' => '',
        'firebase_notification_title' => 'PLN GPS Center',
        'firebase_notification_icon' => 'ic_notification',
        'firebase_default_message' => 'You have a new notification from PLN GPS Center. Please check your app for more details.',
        'firebase_notification_status' => 'active',
        'firebase_test_token' => ''
    ];
    
    $settings = array_merge($defaultSettings, $settings);
    
    return view('settings.system', compact('settings'));
});

// Favicon endpoint for real-time updates
Route::get('/settings/system/favicon', function () {
    $faviconPath = DB::table('site_settings')->where('setting_key', 'favicon_path')->value('setting_value');
    $faviconUrl = $faviconPath ? public_path($faviconPath) : public_path('assets/images/k_favicon_32x.png');
    
    if (file_exists($faviconUrl)) {
        $mimeType = mime_content_type($faviconUrl);
        return response()->file($faviconUrl, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
    
    return response()->file(public_path('assets/images/k_favicon_32x.png'), [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0'
    ]);
});

// Save Site Settings
Route::post('/settings/system/save', function (Request $request) {
    try {
                    $request->validate([
                        'site_name' => 'required|string|max:255',
                        'site_description' => 'required|string|max:500',
                        'footer_text' => 'required|string|max:500',
                        'default_theme' => 'required|in:light,dark,system',
                        'timezone' => 'required|string|max:50',
                        'favicon_size' => 'nullable|integer|min:16|max:64',
                        'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        'logo_icon_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                        'favicon_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
                    ]);

        // Handle logo upload with compression
        $logoPath = null;
        if ($request->hasFile('logo_file')) {
            $logo = $request->file('logo_file');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = 'uploads/site/' . $logoName;
            
            // Create directory if not exists
            $logoDir = public_path('uploads/site');
            if (!file_exists($logoDir)) {
                mkdir($logoDir, 0755, true);
            }
            
            // Move and compress logo
            $logo->move($logoDir, $logoName);
            
            // Logo flexible - no forced resizing, keep original aspect ratio
            // CSS will handle max-width: 150px; max-height: 150px; width: auto; height: auto;
        }

        // Handle logo icon upload (for collapsed sidebar)
        $logoIconPath = null;
        if ($request->hasFile('logo_icon_file')) {
            $logoIcon = $request->file('logo_icon_file');
            $logoIconName = 'logo_icon_' . time() . '.' . $logoIcon->getClientOriginalExtension();
            $logoIconPath = 'uploads/site/' . $logoIconName;
            
            // Create directory if not exists
            $logoIconDir = public_path('uploads/site');
            if (!file_exists($logoIconDir)) {
                mkdir($logoIconDir, 0755, true);
            }
            
            // Move and compress logo icon
            $logoIcon->move($logoIconDir, $logoIconName);
            
            // Auto resize logo icon to 40x40px for collapsed sidebar
            resizeImage($logoIconDir . '/' . $logoIconName, $logoIconDir . '/' . $logoIconName, 40, 40);
        }

        // Handle favicon upload with compression
        $faviconPath = null;
        if ($request->hasFile('favicon_file')) {
            $favicon = $request->file('favicon_file');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $faviconPath = 'uploads/site/' . $faviconName;
            
            // Create directory if not exists
            $faviconDir = public_path('uploads/site');
            if (!file_exists($faviconDir)) {
                mkdir($faviconDir, 0755, true);
            }
            
            // Move and compress favicon
            $favicon->move($faviconDir, $faviconName);
            
            // Auto resize favicon
            $faviconSize = $request->favicon_size ?: 32;
            resizeImage($faviconDir . '/' . $faviconName, $faviconDir . '/' . $faviconName, $faviconSize, $faviconSize);
        }

        // Update settings in database
                    $settingsToUpdate = [
                        'site_name' => $request->site_name,
                        'site_description' => $request->site_description,
                        'footer_text' => $request->footer_text,
                        'default_theme' => $request->default_theme,
                        'timezone' => $request->timezone,
                        'favicon_size' => $request->favicon_size ?: 32,
                    ];

        // Only update file paths if new files were uploaded
        if ($logoPath) {
            $settingsToUpdate['logo_path'] = $logoPath;
        }
        if ($logoIconPath) {
            $settingsToUpdate['logo_icon_path'] = $logoIconPath;
        }
        if ($faviconPath) {
            $settingsToUpdate['favicon_path'] = $faviconPath;
        }

        foreach ($settingsToUpdate as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $value, 'updated_at' => now()]
            );
        }

        // Clear cache after update
        if (class_exists('\App\Helpers\SiteSettings')) {
            \App\Helpers\SiteSettings::clearCache();
        }

        Log::info('Site settings updated', [
            'ip' => $request->ip(),
            'settings' => array_keys($settingsToUpdate)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Site settings berhasil disimpan'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Failed to save site settings', [
            'error' => $e->getMessage(),
            'ip' => $request->ip()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menyimpan settings'
        ], 500);
    }
});

// Helper function for image resizing
if (!function_exists('resizeImage')) {
    function resizeImage($sourcePath, $destinationPath, $width, $height) {
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) return false;
        
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Create image resource from file
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }
        
        if (!$sourceImage) return false;
        
        // Create new image with target dimensions
        $newImage = imagecreatetruecolor($width, $height);
        
        // Preserve transparency for PNG and GIF
        if ($mimeType == 'image/png' || $mimeType == 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
        }
        
        // Resize image
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
        
        // Save resized image
        $result = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $result = imagejpeg($newImage, $destinationPath, 90);
                break;
            case 'image/png':
                $result = imagepng($newImage, $destinationPath, 9);
                break;
            case 'image/gif':
                $result = imagegif($newImage, $destinationPath);
                break;
        }
        
        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return $result;
    }
}

Route::get('/settings/theme', function () {
    return view('settings.theme');
});

Route::get('/settings/site', function () {
    // Load site settings from database
    $settings = DB::table('site_settings')->pluck('setting_value', 'setting_key')->toArray();
    
    // Default values if not set
    $defaultSettings = [
        'site_name' => 'PLN GPS Center',
        'site_description' => 'PLN GPS Center Management System',
        'footer_text' => '© ' . date('Y') . ' PLN GPS Center. Design & Develop by Pixeleyez',
        'default_theme' => 'light',
        'timezone' => 'Asia/Jakarta',
        'logo_path' => '',
        'logo_icon_path' => '',
        'favicon_path' => '',
        'favicon_size' => '32'
    ];
    
    $settings = array_merge($defaultSettings, $settings);
    
    return view('settings.site', compact('settings'));
});

// Notifications placeholder page
Route::get('/settings/notifications', function () {
    return view('settings.notifications');
});

// Transformator settings placeholder
Route::get('/settings/transformator', function () {
    return view('settings.transformator');
});

Route::get('/settings/devices', function () {
    return view('settings.devices');
});

// Transformator API (new)
Route::get('/api/transformator', function (Request $request) {
    $q = trim((string) $request->query('q', ''));
    $perPage = max(1, min(100, (int) $request->query('per_page', 10)));
    $query = TransformatorModel::with(['type', 'garduInduk']);
    if ($q !== '') {
        $query->where(function($w) use ($q) {
            $w->where('kode_aset','like',"%$q%")
              ->orWhere('nomor_seri','like',"%$q%")
              ->orWhere('merk','like',"%$q%")
              ->orWhere('lokasi','like',"%$q%")
              ->orWhere('penyulang','like',"%$q%")
              ->orWhere('gardu','like',"%$q%")
              ->orWhereHas('type', function($t) use ($q) {
                  $t->where('kapasitas_kva','like',"%$q%")
                    ->orWhere('tegangan_primer_kv','like',"%$q%")
                    ->orWhere('tegangan_sekunder_v','like',"%$q%");
              });
        });
    }
    $p = $query->orderByDesc('updated_at')->paginate($perPage)->appends(['q'=>$q,'per_page'=>$perPage]);
    return response()->json($p);
});

Route::post('/api/transformator/save', function (Request $request) {
    \Log::info('Transformator save request:', $request->all());
    $request->validate([
        'kode_aset' => 'required|string|max:50',
        'nomor_seri' => 'nullable|string|max:100',
        'merk' => 'nullable|string|max:100',
        'tahun_operasi' => 'nullable|integer|min:1900|max:2100',
        'lokasi' => 'nullable|string|max:255',
        'koordinat_lat' => 'nullable|numeric',
        'koordinat_long' => 'nullable|numeric',
        'penyulang' => 'nullable|string|max:100',
        'status' => 'nullable|string|max:20',
        'keterangan' => 'nullable|string',
        'type_id' => 'nullable|integer|exists:type_transformator,id',
        'gardu_induk_id' => 'nullable|integer|exists:gardu_induk,id'
    ]);
    $id = $request->input('id');
    if ($id) {
        $row = TransformatorModel::find($id);
        if (!$row) return response()->json(['success'=>false,'message'=>'Not found'],404);
        $row->update($request->only([
            'kode_aset','nomor_seri','merk','tahun_operasi','lokasi','koordinat_lat','koordinat_long',
            'penyulang','status','keterangan','type_id','gardu_induk_id'
        ]));
    } else {
        if (TransformatorModel::where('kode_aset',$request->kode_aset)->exists()) {
            return response()->json(['success'=>false,'message'=>'Kode aset already exists'],422);
        }
        TransformatorModel::create($request->only([
            'kode_aset','nomor_seri','merk','tahun_operasi','lokasi','koordinat_lat','koordinat_long',
            'penyulang','status','keterangan','type_id','gardu_induk_id'
        ]));
    }
    return response()->json(['success'=>true]);
});

Route::post('/api/transformator/delete', function (Request $request) {
    $id = (int) $request->input('id');
    TransformatorModel::where('id',$id)->delete();
    return response()->json(['success'=>true]);
});

Route::post('/api/transformator/bulk', function (Request $request) {
    $action = $request->input('action');
    $ids = (array) $request->input('ids', []);
    if ($action === 'delete') {
        TransformatorModel::whereIn('id',$ids)->delete();
    }
    return response()->json(['success'=>true]);
});

// Type Transformator list for dropdown
Route::get('/api/type-transformator', function (Request $request) {
    $list = TypeTransformator::orderBy('kapasitas_kva')->get();
    return response()->json($list);
});

// Gardu Induk list for dropdown
Route::get('/api/gardu-induk', function (Request $request) {
    $list = \App\Models\GarduInduk::orderBy('nama_gi')->get();
    return response()->json($list);
});

// Show single transformator
Route::get('/api/transformator/show/{id}', function ($id) {
    $row = TransformatorModel::with(['type', 'garduInduk'])->find($id);
    if (!$row) return response()->json(['success'=>false,'message'=>'Not found'],404);
    return response()->json(['success'=>true,'data'=>$row]);
});

// Type Transformator CRUD (paginated list, save, delete, bulk)
Route::get('/api/type-transformator/list', function (Request $request) {
    $q = trim((string) $request->query('q',''));
    $perPage = max(1, min(100, (int) $request->query('per_page', 10)));
    $query = TypeTransformator::query();
    if ($q !== '') {
        $query->where(function($w) use ($q){
            $w->where('kapasitas_kva','like',"%$q%")
              ->orWhere('tegangan_primer_kv','like',"%$q%")
              ->orWhere('tegangan_sekunder_v','like',"%$q%")
              ->orWhere('pendingin','like',"%$q%")
              ->orWhere('jenis_konstruksi','like',"%$q%")
              ->orWhere('isolasi','like',"%$q%");
        });
    }
    $p = $query->orderByDesc('updated_at')->paginate($perPage)->appends(['q'=>$q,'per_page'=>$perPage]);
    return response()->json($p);
});

Route::post('/api/type-transformator/save', function (Request $request) {
    $request->validate([
        'kapasitas_kva' => 'required|integer|min:1',
        'tegangan_primer_kv' => 'required|numeric|min:0',
        'tegangan_sekunder_v' => 'required|integer|min:0',
        'frekuensi_hz' => 'nullable|integer|min:1',
        'impedansi_percent' => 'nullable|numeric|min:0',
        'jumlah_phasa' => 'required|integer|in:1,3',
        'pendingin' => 'nullable|string|max:50',
        'jenis_konstruksi' => 'nullable|string|max:50',
        'isolasi' => 'nullable|string|max:50',
        'fco' => 'nullable|string|max:50',
        'arrester' => 'nullable|string|max:50',
    ]);
    $id = $request->input('id');
    $data = $request->only(['kapasitas_kva','tegangan_primer_kv','tegangan_sekunder_v','frekuensi_hz','impedansi_percent','jumlah_phasa','pendingin','jenis_konstruksi','isolasi','fco','arrester']);
    if ($id) {
        $row = TypeTransformator::find($id);
        if(!$row) return response()->json(['success'=>false,'message'=>'Not found'],404);
        $row->update($data);
    } else {
        TypeTransformator::create($data);
    }
    return response()->json(['success'=>true]);
});

Route::post('/api/type-transformator/delete', function (Request $request) {
    $id = (int) $request->input('id');
    TypeTransformator::where('id',$id)->delete();
    return response()->json(['success'=>true]);
});

Route::post('/api/type-transformator/bulk', function (Request $request) {
    $action = $request->input('action');
    $ids = (array) $request->input('ids', []);
    if ($action === 'delete') {
        TypeTransformator::whereIn('id',$ids)->delete();
    }
    return response()->json(['success'=>true]);
});

// Gardu Induk CRUD API Routes
Route::get('/api/gardu-induk/list', function (Request $request) {
    $q = trim((string) $request->query('q',''));
    $perPage = max(1, min(100, (int) $request->query('per_page', 10)));
    $query = \App\Models\GarduInduk::query();
    if ($q !== '') {
        $query->where(function($w) use ($q){
            $w->where('nama_gi','like',"%$q%")
              ->orWhere('kode_gi','like',"%$q%")
              ->orWhere('alamat','like',"%$q%")
              ->orWhere('kabupaten','like',"%$q%")
              ->orWhere('provinsi','like',"%$q%");
        });
    }
    $p = $query->orderByDesc('updated_at')->paginate($perPage)->appends(['q'=>$q,'per_page'=>$perPage]);
    return response()->json($p);
});

Route::post('/api/gardu-induk/save', function (Request $request) {
    $request->validate([
        'kode_gi' => 'required|string|max:50',
        'nama_gi' => 'required|string|max:100',
        'alamat' => 'required|string',
        'koordinat_lat' => 'nullable|numeric|between:-90,90',
        'koordinat_long' => 'nullable|numeric|between:-180,180',
        'kabupaten' => 'nullable|string|max:100',
        'provinsi' => 'nullable|string|max:100',
        'tegangan_level_in_kv' => 'nullable|numeric|min:0',
        'tegangan_level_out_kv' => 'nullable|numeric|min:0',
        'jumlah_bay' => 'nullable|integer|min:1',
        'kapasitas_mva' => 'nullable|numeric|min:0',
        'tahun_operasi' => 'nullable|integer|min:1900|max:2100',
        'status' => 'required|string|in:aktif,non_aktif,maintenance',
        'keterangan' => 'nullable|string'
    ]);
    
    $id = $request->input('id');
    $data = $request->only([
        'kode_gi', 'nama_gi', 'alamat', 'koordinat_lat', 'koordinat_long',
        'kabupaten', 'provinsi', 'tegangan_level_in_kv', 'tegangan_level_out_kv',
        'jumlah_bay', 'kapasitas_mva', 'tahun_operasi', 'status', 'keterangan'
    ]);
    
    if ($id) {
        $row = \App\Models\GarduInduk::find($id);
        if(!$row) return response()->json(['success'=>false,'message'=>'Not found'],404);
        $row->update($data);
    } else {
        if (\App\Models\GarduInduk::where('kode_gi',$request->kode_gi)->exists()) {
            return response()->json(['success'=>false,'message'=>'Kode Gardu Induk sudah ada'],422);
        }
        \App\Models\GarduInduk::create($data);
    }
    return response()->json(['success'=>true]);
});

Route::post('/api/gardu-induk/delete', function (Request $request) {
    $id = (int) $request->input('id');
    \App\Models\GarduInduk::where('id',$id)->delete();
    return response()->json(['success'=>true]);
});

Route::post('/api/gardu-induk/bulk', function (Request $request) {
    $action = $request->input('action');
    $ids = (array) $request->input('ids', []);
    if ($action === 'delete') {
        \App\Models\GarduInduk::whereIn('id',$ids)->delete();
    }
    return response()->json(['success'=>true]);
});

// Locations API for dropdown
Route::get('/api/locations', function (Request $request) {
    $q = trim((string) $request->query('q', ''));
    $query = Location::query();
    if ($q !== '') {
        $query->where(function($w) use ($q) {
            $w->where('name','like',"%$q%")
              ->orWhere('address','like',"%$q%")
              ->orWhere('city','like',"%$q%");
        });
    }
    return response()->json($query->orderBy('name')->limit(50)->get());
});
Route::get('/settings/notifications/list', function () {
    $rows = NotificationTemplate::orderByDesc('updated_at')->get();
    return response()->json(['success' => true, 'data' => $rows]);
});

Route::post('/settings/notifications/save', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:email,whatsapp,push',
        'status' => 'required|in:active,inactive',
        'content' => 'nullable|string'
    ]);
    $id = $request->input('id');
    if ($id) {
        $tpl = NotificationTemplate::find($id);
        if (!$tpl) return response()->json(['success'=>false,'message'=>'Template not found'],404);
        $tpl->update([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
            'content' => $request->content,
            'trigger_sensor' => $request->trigger_sensor,
            'trigger_condition' => $request->trigger_condition,
            'trigger_value' => $request->trigger_value,
        ]);
    } else {
        NotificationTemplate::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
            'content' => $request->content,
            'trigger_sensor' => $request->trigger_sensor,
            'trigger_condition' => $request->trigger_condition,
            'trigger_value' => $request->trigger_value,
        ]);
    }
    return response()->json(['success' => true]);
});

Route::post('/settings/notifications/delete', function (Request $request) {
    $id = (string) $request->input('id');
    NotificationTemplate::where('id', $id)->delete();
    return response()->json(['success' => true]);
});

Route::post('/settings/notifications/bulk', function (Request $request) {
    $action = $request->input('action');
    $ids = (array) $request->input('ids', []);
    if ($action === 'delete') {
        NotificationTemplate::whereIn('id', $ids)->delete();
    } elseif (in_array($action, ['activate','deactivate'], true)) {
        NotificationTemplate::whereIn('id', $ids)->update(['status' => $action === 'activate' ? 'active' : 'inactive']);
    }
    return response()->json(['success' => true]);
});

Route::get('/settings/wa', function () {
    return view('settings.wa');
});
// WA notification settings APIs
Route::get('/api/wa/audience-options', function () {
    $groups = DB::table('user_groups')->select('id','name')->orderBy('name')->get();
    $users = DB::table('users')->select('id','name','email')->orderBy('name')->limit(500)->get();
    $templates = NotificationTemplate::orderBy('name')->get(['id','name','type','status']);
    return response()->json(['groups'=>$groups,'users'=>$users,'templates'=>$templates]);
});

Route::get('/api/wa/setting', function(){
    $row = WhatsappNotificationSetting::first();
    return response()->json($row);
});

Route::post('/api/wa/setting/save', function (Request $request) {
    $request->validate([
        'audience_type' => 'required|in:all,group,users',
        'audience_ids' => 'nullable|array',
        'template_id' => 'nullable|integer|exists:notification_templates,id'
    ]);
    $row = WhatsappNotificationSetting::first();
    $data = [
        'audience_type' => $request->audience_type,
        'audience_ids' => $request->audience_ids,
        'template_id' => $request->template_id,
    ];
    if ($row) { $row->update($data); } else { WhatsappNotificationSetting::create($data); }
    return response()->json(['success'=>true]);
});

Route::get('/users/add', function () {
    $users = DB::table('users')
        ->leftJoin('positions', 'users.position_id', '=', 'positions.id')
        ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
        ->leftJoin('user_groups', 'users.group_id', '=', 'user_groups.id')
        ->select(
            'users.id',
            'users.name',
            'users.role',
            'users.status',
            'positions.name as position_name',
            'departments.name as department_name',
            'user_groups.name as group_name'
        )
        ->where('users.role', '!=', 'super_admin') // Hide only super_admin from list
        ->orderByDesc('users.id')
        ->limit(100)
        ->get();
    $positions = DB::table('positions')->select('id','name')->orderBy('name')->get();
    $departments = DB::table('departments')->select('id','name')->orderBy('name')->get();
    $groups = DB::table('user_groups')->select('id','name')->orderBy('name')->get();
    return view('users.add', compact('users','positions','departments','groups'));
});

// Bulk delete users
Route::post('/users/bulk-delete', function (Request $request) {
    try {
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No users selected'
            ], 400);
        }
        
        // Prevent deleting super_admin
        $deletedCount = DB::table('users')
            ->whereIn('id', $userIds)
            ->where('role', '!=', 'super_admin')
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => $deletedCount . ' user(s) deleted successfully'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete users: ' . $e->getMessage()
        ], 500);
    }
});

// Bulk update user status
Route::post('/users/bulk-status', function (Request $request) {
    try {
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No users selected'
            ], 400);
        }
        
        // Toggle status for each user
        $updatedCount = 0;
        foreach ($userIds as $userId) {
            $user = DB::table('users')
                ->where('id', $userId)
                ->where('role', '!=', 'super_admin')
                ->first();
            
            if ($user) {
                $newStatus = $user->status == 1 ? 0 : 1;
                DB::table('users')
                    ->where('id', $userId)
                    ->update(['status' => $newStatus]);
                $updatedCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => $updatedCount . ' user(s) status updated successfully'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update user status: ' . $e->getMessage()
        ], 500);
    }
});

// Create new user
Route::post('/users/create', function (Request $request) {
    try {
        $request->validate([
            'employee_code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'position_id' => 'required|integer',
            'department_id' => 'required|integer',
                        'role' => 'required|in:super_admin,admin,user',
            'user_group_id' => 'required|integer',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|file'
        ]);

        // Check if referenced IDs exist
        $position = DB::table('positions')->where('id', $request->position_id)->exists();
        $department = DB::table('departments')->where('id', $request->department_id)->exists();
        $group = DB::table('user_groups')->where('id', $request->user_group_id)->exists();

        if (!$position || !$department || !$group) {
            return response()->json([
                'success' => false,
                'message' => 'Position, Department, atau Group tidak valid'
            ], 400);
        }

        // Handle avatar upload (optional)
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            
            // Validate file is valid and readable
            if (!$avatar->isValid()) {
                throw new \Exception('File avatar tidak valid atau rusak: ' . $avatar->getErrorMessage());
            }
            
            // Alternative approach: use file_get_contents to avoid temporary file issues
            try {
                $fileContent = file_get_contents($avatar->getRealPath());
                if ($fileContent === false) {
                    throw new \Exception('Tidak dapat membaca file avatar');
                }
            } catch (\Exception $e) {
                // Fallback to direct get if file_get_contents fails
                try {
                    $fileContent = $avatar->get();
                    if ($fileContent === false) {
                        throw new \Exception('Tidak dapat membaca konten file avatar');
                    }
                } catch (\Exception $e2) {
                    throw new \Exception('File avatar tidak dapat dibaca: ' . $e2->getMessage());
                }
            }
            
            // Ensure upload directory exists
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatarPath = 'uploads/avatars/' . $avatarName;
            $fullPath = public_path($avatarPath);
            
            // Check if file content is not empty
            if (empty($fileContent)) {
                throw new \Exception('File avatar kosong atau tidak valid');
            }
            
            // Write file content directly
            if (file_put_contents($fullPath, $fileContent) === false) {
                throw new \Exception('Gagal menyimpan file avatar ke direktori upload');
            }
        }

        // Insert user
        $userId = DB::table('users')->insertGetId([
            'employee_code' => $request->employee_code,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position_id' => $request->position_id,
            'department_id' => $request->department_id,
            'role' => $request->role,
            'group_id' => $request->user_group_id,
            'status' => 1, // Active
            'password' => password_hash($request->password, PASSWORD_DEFAULT),
            'avatar_path' => $avatarPath, // NULL if no avatar uploaded (will use default)
            'avatar_mime' => $request->hasFile('avatar') ? $request->file('avatar')->getMimeType() : null,
            'avatar_size' => $request->hasFile('avatar') ? $request->file('avatar')->getSize() : null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Log::info('User created successfully', [
            'user_id' => $userId,
            'email' => $request->email,
            'name' => $request->name,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat',
            'user_id' => $userId
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Failed to create user', [
            'error' => $e->getMessage(),
            'email' => $request->email ?? 'unknown',
            'ip' => $request->ip()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menyimpan user'
        ], 500);
    }
});

// Check if email or phone already exists (smart validation)
Route::get('/users/check-duplicate', function (Request $request) {
    $email = trim((string) $request->query('email', ''));
    $phone = trim((string) $request->query('phone', ''));
    $excludeId = (int) $request->query('exclude_id', 0); // For edit mode
    
    $result = [
        'email' => ['exists' => false, 'message' => ''],
        'phone' => ['exists' => false, 'message' => '']
    ];
    
    // Check email
    if ($email !== '') {
        $emailQuery = DB::table('users')->where('email', $email);
        if ($excludeId > 0) {
            $emailQuery->where('id', '!=', $excludeId);
        }
        $emailExists = $emailQuery->exists();
        
        if ($emailExists) {
            $result['email'] = [
                'exists' => true,
                'message' => 'Email sudah digunakan oleh user lain'
            ];
        }
    }
    
    // Check phone
    if ($phone !== '') {
        $phoneQuery = DB::table('users')->where('phone', $phone);
        if ($excludeId > 0) {
            $phoneQuery->where('id', '!=', $excludeId);
        }
        $phoneExists = $phoneQuery->exists();
        
        if ($phoneExists) {
            $result['phone'] = [
                'exists' => true,
                'message' => 'Nomor HP sudah digunakan oleh user lain'
            ];
        }
    }
    
    return response()->json(['ok' => true, 'data' => $result]);
});

// Verify user exists by email and log a warning if not found (post-success toast check)
Route::get('/users/check-exists', function (Request $request) {
    $email = trim((string) $request->query('email', ''));
    if ($email === '') {
        return response()->json(['ok' => false, 'exists' => false, 'message' => 'Email kosong'], 400);
    }
    $exists = DB::table('users')->where('email', $email)->exists();
    if (!$exists) {
        Log::warning('Success toast shown but user not found in database', [
            'email' => $email,
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
        ]);
    }
    return response()->json(['ok' => true, 'exists' => $exists]);
});

Route::get('/users/group', function () {
    // Get all groups
    $groups = DB::table('user_groups')
        ->orderBy('name')
        ->get();
    
    // Get group leaders
    $groupLeaders = [];
    $leaders = DB::table('group_leaders')->get();
    foreach ($leaders as $leader) {
        $groupLeaders[$leader->group_id] = $leader->user_id;
    }
    
    // Get users grouped by group_id
    $groupUsers = [];
    foreach ($groups as $group) {
        $users = DB::table('users')
            ->where('group_id', $group->id)
            ->select('id', 'name', 'email', 'role', 'avatar_path')
            ->get();
        
        // Sort: leaders first, then members
        $leaderId = $groupLeaders[$group->id] ?? null;
        $groupUsers[$group->id] = $users->sortBy(function($user) use ($leaderId) {
            return $user->id == $leaderId ? 0 : 1;
        })->values();
    }
    
    // Get unassigned users (group_id is null or 0)
    $unassignedUsers = DB::table('users')
        ->where(function($query) {
            $query->whereNull('group_id')
                  ->orWhere('group_id', 0);
        })
        ->select('id', 'name', 'email', 'role', 'avatar_path')
        ->orderBy('name')
        ->get();
    
    return view('users.group', compact('groups', 'groupUsers', 'groupLeaders', 'unassignedUsers'));
});

Route::get('/users/master', function () {
    $positions = DB::table('positions')->select('id','name','status')->orderBy('name')->get();
    $departments = DB::table('departments')->select('id','name','status')->orderBy('name')->get();
    $groups = DB::table('user_groups')->select('id','name')->orderBy('name')->get();
    return view('users.master', compact('positions','departments','groups'));
});

// Store endpoints (simple, no auth middleware for now)
Route::post('/users/master/position', function (Request $request) {
    $name = trim($request->input('position_name'));
    if ($name !== '') {
        Position::firstOrCreate([ 'name' => $name ], [ 'status' => 1 ]);
    }
    return redirect('/users/master');
});

Route::post('/users/master/department', function (Request $request) {
    $name = trim($request->input('department_name'));
    if ($name !== '') {
        Department::firstOrCreate([ 'name' => $name ], [ 'status' => 1 ]);
    }
    return redirect('/users/master');
});

Route::post('/users/master/group', function (Request $request) {
    $name = trim($request->input('group_name'));
    $desc = trim($request->input('group_desc')) ?: null;
    if ($name !== '') {
        // Use only available columns to avoid errors on existing schemas
        $group = UserGroup::firstOrCreate([ 'name' => $name ]);
        if ($desc !== null) {
            $group->description = $desc;
            try { $group->save(); } catch (\Throwable $e) {}
        }
    }
    return redirect('/users/master');
});

Route::get('/monitoring/trafo', function () {
    return view('dashboard'); // placeholder content for Monitoring Trafo
});

// User Profile Page
Route::get('/profile/{id?}', function (Request $request, $id = null) {
    // If no ID provided, show current logged user or first non-super_admin user as demo
    if (!$id) {
        $user = DB::table('users')
            ->where('role', '!=', 'super_admin')
            ->first();
    } else {
        $user = DB::table('users')->where('id', $id)->first();
    }
    
    if (!$user) {
        return redirect('/dashboard')->with('error', 'User not found');
    }
    
    // Get related data
    $position = $user->position_id ? DB::table('positions')->where('id', $user->position_id)->first() : null;
    $department = $user->department_id ? DB::table('departments')->where('id', $user->department_id)->first() : null;
    $userGroup = $user->group_id ? DB::table('user_groups')->where('id', $user->group_id)->first() : null;
    
    // Get master data for edit form
    $positions = DB::table('positions')->select('id','name')->orderBy('name')->get();
    $departments = DB::table('departments')->select('id','name')->orderBy('name')->get();
    $groups = DB::table('user_groups')->select('id','name')->orderBy('name')->get();
    
    return view('users.profile', compact('user', 'position', 'department', 'userGroup', 'positions', 'departments', 'groups'));
});

// Update Profile
Route::post('/profile/update', function (Request $request) {
    try {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'position_id' => 'nullable|integer|exists:positions,id',
            'department_id' => 'nullable|integer|exists:departments,id',
            'group_id' => 'nullable|integer|exists:user_groups,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|file',
        ]);

        $userId = $request->user_id;
        
        // Check if email already exists for other users
        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->where('id', '!=', $userId)
            ->exists();
            
        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah digunakan oleh user lain'
            ], 400);
        }

        // Check if phone already exists for other users
        $phoneExists = DB::table('users')
            ->where('phone', $request->phone)
            ->where('id', '!=', $userId)
            ->exists();
            
        if ($phoneExists) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP sudah digunakan oleh user lain'
            ], 400);
        }

        // Handle avatar upload (optional)
        $avatarPath = null;
        $avatarMime = null;
        $avatarSize = null;
        
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            
            // Validate file is valid and readable
            if (!$avatar->isValid()) {
                throw new \Exception('File avatar tidak valid atau rusak: ' . $avatar->getErrorMessage());
            }
            
            // Alternative approach: use file_get_contents to avoid temporary file issues
            try {
                $fileContent = file_get_contents($avatar->getRealPath());
                if ($fileContent === false) {
                    throw new \Exception('Tidak dapat membaca file avatar');
                }
            } catch (\Exception $e) {
                // Fallback to direct move if file_get_contents fails
                try {
                    $fileContent = $avatar->get();
                    if ($fileContent === false) {
                        throw new \Exception('Tidak dapat membaca konten file avatar');
                    }
                } catch (\Exception $e2) {
                    throw new \Exception('File avatar tidak dapat dibaca: ' . $e2->getMessage());
                }
            }
            
            // Ensure upload directory exists
            $uploadDir = public_path('uploads/avatars');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatarPath = 'uploads/avatars/' . $avatarName;
            $fullPath = public_path($avatarPath);
            
            // Check if file content is not empty
            if (empty($fileContent)) {
                throw new \Exception('File avatar kosong atau tidak valid');
            }
            
            // Write file content directly
            if (file_put_contents($fullPath, $fileContent) === false) {
                throw new \Exception('Gagal menyimpan file avatar ke direktori upload');
            }
            
            $avatarMime = $avatar->getMimeType();
            $avatarSize = strlen($fileContent);
        }

        // Update user
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position_id' => $request->position_id,
            'department_id' => $request->department_id,
            'group_id' => $request->group_id,
            'updated_at' => now()
        ];

        // Only update avatar fields if new avatar was uploaded
        if ($request->hasFile('avatar')) {
            $updateData['avatar_path'] = $avatarPath;
            $updateData['avatar_mime'] = $avatarMime;
            $updateData['avatar_size'] = $avatarSize;
            $updateData['avatar_updated_at'] = now();
        }

        DB::table('users')->where('id', $userId)->update($updateData);

        Log::info('User profile updated', [
            'user_id' => $userId,
            'email' => $request->email,
            'name' => $request->name,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diperbarui'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Failed to update profile', [
            'error' => $e->getMessage(),
            'user_id' => $request->user_id ?? 'unknown',
            'ip' => $request->ip(),
            'has_file' => $request->hasFile('avatar'),
            'file_valid' => $request->hasFile('avatar') ? $request->file('avatar')->isValid() : 'no file',
            'file_error' => $request->hasFile('avatar') && !$request->file('avatar')->isValid() ? $request->file('avatar')->getErrorMessage() : 'no error'
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memperbarui profile: ' . $e->getMessage()
        ], 500);
    }
});

// Save SMTP Settings
Route::post('/settings/smtp/save', function (Request $request) {
    try {
        $request->validate([
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_username' => 'required|email|max:255',
            'smtp_password' => 'required|string|max:255',
            'smtp_encryption' => 'required|in:tls,ssl,none',
            'smtp_from_name' => 'required|string|max:255'
        ]);

        $smtpSettings = [
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'smtp_username' => $request->smtp_username,
            'smtp_password' => $request->smtp_password,
            'smtp_encryption' => $request->smtp_encryption,
            'smtp_from_name' => $request->smtp_from_name
        ];

        foreach ($smtpSettings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $value, 'updated_at' => now()]
            );
        }

        // Clear cache
        if (class_exists('\App\Helpers\SiteSettings')) {
            \App\Helpers\SiteSettings::clearCache();
        }

        return response()->json([
            'success' => true,
            'message' => 'SMTP settings berhasil disimpan!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
});

// Test SMTP Connection (send a test email using provided settings without persisting)
Route::post('/settings/smtp/test', function (Request $request) {
    try {
        $request->validate([
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_username' => 'required|email|max:255',
            'smtp_password' => 'required|string|max:255',
            'smtp_encryption' => 'required|in:tls,ssl,none',
            'smtp_from_name' => 'required|string|max:255',
            'test_email' => 'required|email|max:255'
        ]);

        $host = $request->smtp_host;
        $port = (int) $request->smtp_port;
        $username = $request->smtp_username;
        $password = $request->smtp_password;
        $encryption = $request->smtp_encryption;
        $fromName = $request->smtp_from_name;
        $toEmail = $request->test_email;
        
        // Raw SMTP flow using fsockopen (supports SSL and STARTTLS)
        $timeout = 15;
        $targetHost = $encryption === 'ssl' ? 'ssl://' . $host : $host;
        $errno = 0; $errstr = '';
        $fp = @fsockopen($targetHost, $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat terhubung ke SMTP server: ' . ($errstr ?: 'Unknown error')], 400);
        }
        stream_set_timeout($fp, $timeout);

        $lastResponse = '';
        $readResponse = function() use ($fp, &$lastResponse) {
            $data = '';
            // Wait until socket is readable to avoid empty reads (banner timing)
            $read = [$fp]; $write = null; $except = null;
            // up to 10 seconds
            if (stream_select($read, $write, $except, 10) === 0) {
                return '';
            }
            while (($line = fgets($fp, 515)) !== false) {
                $data .= $line;
                if (preg_match('/^\d{3} [\s\S]*/', $line)) {
                    break;
                }
                // If multiline (e.g., 250-), continue until space after code
                if (!preg_match('/^\d{3}-/', $line)) {
                    break;
                }
            }
            $lastResponse = trim($data);
            return $data;
        };

        $expect = function(array $codes, $stepLabel = '') use ($readResponse, &$lastResponse) {
            $resp = $readResponse();
            foreach ($codes as $code) {
                if (strpos($resp, (string)$code) === 0) {
                    return $resp;
                }
            }
            \Log::error('SMTP test unexpected response', [ 'step' => $stepLabel, 'response' => trim($resp) ]);
            throw new \Exception('Unexpected SMTP response: ' . trim($resp));
        };

        $send = function($cmd) use ($fp) {
            fwrite($fp, $cmd . "\r\n");
        };

        $expect([220], 'banner');
        $send('EHLO ' . parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST));
        $ehloResp = $expect([250], 'ehlo-1');

        if ($encryption === 'tls') {
            // try STARTTLS
            $send('STARTTLS');
            $expect([220], 'starttls');
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                throw new \Exception('Gagal melakukan negosiasi TLS (STARTTLS)');
            }
            // EHLO again after TLS
            $send('EHLO ' . parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST));
            $expect([250], 'ehlo-2');
        }

        // AUTH LOGIN
        $send('AUTH LOGIN');
        $expect([334], 'auth-login');
        $send(base64_encode($username));
        $expect([334], 'auth-user');
        $send(base64_encode($password));
        $expect([235], 'auth-pass');

        // MAIL FROM / RCPT TO
        $send('MAIL FROM: <' . $username . '>');
        $expect([250], 'mail-from');
        $send('RCPT TO: <' . $toEmail . '>');
        $expect([250, 251], 'rcpt-to');

        // DATA
        $send('DATA');
        $expect([354], 'data');
        $headers = [];
        $headers[] = 'From: ' . $fromName . ' <' . $username . '>';
        $headers[] = 'To: <' . $toEmail . '>';
        $headers[] = 'Subject: SMTP Test - PLN GPS Center';
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';
        $message = 'Ini adalah email uji coba untuk memastikan konfigurasi SMTP berfungsi.';
        $payload = implode("\r\n", $headers) . "\r\n\r\n" . $message . "\r\n.";
        fwrite($fp, $payload . "\r\n");
        $expect([250], 'data-end');

        // QUIT
        $send('QUIT');
        fclose($fp);

        return response()->json(['success' => true, 'message' => 'Email test berhasil dikirim ke ' . $toEmail]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Throwable $e) {
        \Log::error('SMTP test failed', [ 'error' => $e->getMessage() ]);
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim email test: ' . $e->getMessage()
        ], 500);
    }
});

// Save Google Map Settings
Route::post('/settings/google-map/save', function (Request $request) {
    try {
        $request->validate([
            'google_maps_api_key' => 'required|string|max:255',
            'default_latitude' => 'required|numeric|between:-90,90',
            'default_longitude' => 'required|numeric|between:-180,180',
            'default_zoom' => 'required|integer|min:1|max:20',
            'default_map_type' => 'required|in:roadmap,satellite,hybrid,terrain'
        ]);

        $googleMapSettings = [
            'google_maps_api_key' => $request->google_maps_api_key,
            'default_latitude' => $request->default_latitude,
            'default_longitude' => $request->default_longitude,
            'default_zoom' => $request->default_zoom,
            'default_map_type' => $request->default_map_type
        ];

        foreach ($googleMapSettings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $value, 'updated_at' => now()]
            );
        }

        // Clear cache
        if (class_exists('\App\Helpers\SiteSettings')) {
            \App\Helpers\SiteSettings::clearCache();
        }

        return response()->json([
            'success' => true,
            'message' => 'Google Map settings berhasil disimpan!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
});

// Test Google Map API Key
Route::post('/settings/google-map/test', function (Request $request) {
    try {
        $request->validate([
            'google_maps_api_key' => 'required|string|max:255'
        ]);

        $apiKey = trim($request->google_maps_api_key);

        // Use a simple Geocoding request to validate the key
        $testUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=Jakarta&key=' . urlencode($apiKey);

        $responseBody = null;
        $httpStatus = 0;

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $testUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ]);
            $responseBody = curl_exec($ch);
            if ($responseBody === false) {
                $err = curl_error($ch);
                curl_close($ch);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi Google API: ' . $err
                ], 500);
            }
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 12,
                    'header' => "Accept: application/json\r\n"
                ]
            ]);
            $responseBody = @file_get_contents($testUrl, false, $context);
            if (isset($http_response_header) && is_array($http_response_header)) {
                foreach ($http_response_header as $h) {
                    if (preg_match('/^HTTP\/\S+\s(\d{3})/', $h, $m)) { $httpStatus = (int)$m[1]; break; }
                }
            }
            if ($responseBody === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi Google API (stream)'
                ], 500);
            }
        }

        $json = json_decode($responseBody, true);
        $status = $json['status'] ?? null;
        $errorMessage = $json['error_message'] ?? null;

        if ($httpStatus === 200 && $status === 'OK') {
            return response()->json([
                'success' => true,
                'message' => 'API Key valid. Geocoding berhasil.'
            ]);
        }

        $detail = $errorMessage ?: ('Status: ' . ($status ?: 'UNKNOWN'));
        return response()->json([
            'success' => false,
            'message' => 'API Key tidak valid atau dibatasi. ' . $detail
        ], 400);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menguji Google Map API: ' . $e->getMessage()
        ], 500);
    }
});

// Save WhatsApp Settings
Route::post('/settings/whatsapp/save', function (Request $request) {
    try {
        $request->validate([
            'whatsapp_api_key' => 'required|string|max:255',
            'whatsapp_phone_number_id' => 'nullable|string|max:255',
            'whatsapp_business_account_id' => 'nullable|string|max:255',
            'whatsapp_webhook_url' => 'nullable|url|max:500',
            'whatsapp_webhook_verify_token' => 'nullable|string|max:255',
            'whatsapp_default_template' => 'nullable|string|max:1000',
            'whatsapp_notification_status' => 'required|in:active,inactive',
            'whatsapp_test_phone' => 'nullable|string|max:20'
        ]);

        $whatsappSettings = [
            'whatsapp_api_key' => $request->whatsapp_api_key,
            'whatsapp_phone_number_id' => $request->whatsapp_phone_number_id,
            'whatsapp_business_account_id' => $request->whatsapp_business_account_id,
            'whatsapp_webhook_url' => $request->whatsapp_webhook_url,
            'whatsapp_webhook_verify_token' => $request->whatsapp_webhook_verify_token,
            'whatsapp_default_template' => $request->whatsapp_default_template,
            'whatsapp_notification_status' => $request->whatsapp_notification_status,
            'whatsapp_test_phone' => $request->whatsapp_test_phone
        ];

        foreach ($whatsappSettings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $value, 'updated_at' => now()]
            );
        }

        // Clear cache
        if (class_exists('\App\Helpers\SiteSettings')) {
            \App\Helpers\SiteSettings::clearCache();
        }

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp settings berhasil disimpan!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
});

// Test WhatsApp API via Fonnte
Route::post('/settings/whatsapp/test', function (Request $request) {
    try {
        $request->validate([
            'whatsapp_api_key' => 'required|string|max:255',
            'target' => 'required|string|max:30',
            'message' => 'required|string|max:1000',
            'country_code' => 'nullable|string|max:5'
        ]);

        $token = trim($request->whatsapp_api_key);
        $target = preg_replace('/\s+/', '', (string)$request->target);
        $message = (string)$request->message;
        $country = trim((string)$request->country_code ?: '62');

        if (!function_exists('curl_init')) {
            return response()->json([
                'success' => false,
                'message' => 'cURL PHP tidak tersedia di server'
            ], 500);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message,
                'countryCode' => $country
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $token
            ],
        ]);

        $respBody = curl_exec($ch);
        if ($respBody === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi Fonnte: ' . $err
            ], 500);
        }
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($respBody, true);
        $ok = false;
        $detail = null;
        if (is_array($json)) {
            // Fonnte biasanya mengembalikan status atau detail pada JSON
            $ok = ($json['status'] ?? $json['success'] ?? false) ? true : false;
            $detail = $json['detail'] ?? $json['message'] ?? null;
        }

        if ($statusCode >= 200 && $statusCode < 300 && ($ok || $json !== null)) {
            return response()->json([
                'success' => true,
                'message' => 'Test WhatsApp terkirim (Fonnte).',
                'provider_response' => $json ?: $respBody
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Fonnte mengembalikan error: HTTP ' . $statusCode . ($detail ? (' - ' . $detail) : ''),
            'provider_response' => $json ?: $respBody
        ], 400);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menguji WhatsApp API: ' . $e->getMessage()
        ], 500);
    }
});

// Save Firebase Settings
Route::post('/settings/firebase/save', function (Request $request) {
    try {
        $request->validate([
            'firebase_server_key' => 'required|string|max:255',
            'firebase_project_id' => 'nullable|string|max:255',
            'firebase_app_id' => 'nullable|string|max:255',
            'firebase_config_json' => 'nullable|string|max:2000',
            'firebase_notification_title' => 'required|string|max:255',
            'firebase_notification_icon' => 'required|string|max:255',
            'firebase_default_message' => 'nullable|string|max:1000',
            'firebase_notification_status' => 'required|in:active,inactive',
            'firebase_test_token' => 'nullable|string|max:500'
        ]);

        $firebaseSettings = [
            'firebase_server_key' => $request->firebase_server_key,
            'firebase_project_id' => $request->firebase_project_id,
            'firebase_app_id' => $request->firebase_app_id,
            'firebase_config_json' => $request->firebase_config_json,
            'firebase_notification_title' => $request->firebase_notification_title,
            'firebase_notification_icon' => $request->firebase_notification_icon,
            'firebase_default_message' => $request->firebase_default_message,
            'firebase_notification_status' => $request->firebase_notification_status,
            'firebase_test_token' => $request->firebase_test_token
        ];

        foreach ($firebaseSettings as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $value, 'updated_at' => now()]
            );
        }

        // Clear cache
        if (class_exists('\App\Helpers\SiteSettings')) {
            \App\Helpers\SiteSettings::clearCache();
        }

        return response()->json([
            'success' => true,
            'message' => 'Firebase settings berhasil disimpan!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
});

// Auth Routes
Route::get('/auth-reset-password', function () {
    return view('auth-reset-password');
})->name('auth.reset-password');

Route::get('/auth-create-password', function () {
    return view('auth-create-password');
})->name('auth.create-password');

Route::get('/auth-signin', function () {
    return view('auth-signin');
})->name('auth.signin');

Route::get('/auth-signup', function () {
    return view('auth-signup');
})->name('auth.signup');

// Forgot Password
Route::post('/forgot-password', function (Request $request) {
    try {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $email = $request->email;
        
        // Simulate sending reset email (in real implementation, you would send actual email)
        // For now, we'll just return success message
        
        // You can add actual email sending logic here
        // Example: Mail::to($email)->send(new PasswordResetMail($resetToken));
        
        return response()->json([
            'success' => true,
            'message' => 'Password reset link has been sent to ' . $email . '. Please check your email inbox.'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send reset link. Please try again later.'
        ], 500);
    }
})->name('forgot-password');

// Update Job Info
Route::post('/profile/update-job-info', function (Request $request) {
    try {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'employee_code' => 'required|string|max:50',
            'position_id' => 'required|integer|exists:positions,id',
            'department_id' => 'required|integer|exists:departments,id',
            'role' => 'required|in:user,admin,super_admin',
            'group_id' => 'required|integer|exists:user_groups,id',
            'status' => 'required|in:0,1'
        ]);

        $userId = $request->user_id;
        
        // Check if employee code already exists for other users
        $employeeCodeExists = DB::table('users')
            ->where('employee_code', $request->employee_code)
            ->where('id', '!=', $userId)
            ->exists();
            
        if ($employeeCodeExists) {
            return response()->json([
                'success' => false,
                'message' => 'Employee code sudah digunakan oleh user lain'
            ], 400);
        }

        // Update job information
        $updateData = [
            'employee_code' => $request->employee_code,
            'position_id' => $request->position_id,
            'department_id' => $request->department_id,
            'role' => $request->role,
            'group_id' => $request->group_id,
            'status' => $request->status,
            'updated_at' => now()
        ];

        DB::table('users')
            ->where('id', $userId)
            ->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Job information berhasil diperbarui!'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
});

// Assign user to group (drag & drop)
Route::post('/users/assign-group', function (Request $request) {
    try {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'group_id' => 'required|integer'
        ]);

        $userId = $request->user_id;
        $groupId = $request->group_id;
        
        // If group_id is 0, set to null (unassigned)
        $groupId = $groupId == 0 ? null : $groupId;
        
        // Update user's group
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'group_id' => $groupId,
                'updated_at' => now()
            ]);

        // Enforce leader rule: if user was a leader anywhere, remove leadership when moved or unassigned
        DB::table('group_leaders')->where('user_id', $userId)->delete();

        $message = $groupId ? 'User assigned to group successfully' : 'User removed from group successfully (leader status cleared if any)';
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to assign user: ' . $e->getMessage()
        ], 500);
    }
});

// Update group
Route::post('/users/group/update', function (Request $request) {
    try {
        $request->validate([
            'group_id' => 'required|integer|exists:user_groups,id',
            'group_name' => 'required|string|max:255',
            'group_desc' => 'nullable|string|max:500'
        ]);

        DB::table('user_groups')
            ->where('id', $request->group_id)
            ->update([
                'name' => $request->group_name,
                'description' => $request->group_desc
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Group updated successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update group: ' . $e->getMessage()
        ], 500);
    }
});

// Delete group
Route::post('/users/group/delete', function (Request $request) {
    try {
        $request->validate([
            'group_id' => 'required|integer|exists:user_groups,id'
        ]);

        $groupId = $request->group_id;
        
        // Unassign users from this group
        DB::table('users')
            ->where('group_id', $groupId)
            ->update([
                'group_id' => null,
                'updated_at' => now()
            ]);
        
        // Delete the group
        DB::table('user_groups')
            ->where('id', $groupId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully. Users have been unassigned.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete group: ' . $e->getMessage()
        ], 500);
    }
});

// Set user as group leader
Route::post('/users/group/set-leader', function (Request $request) {
    try {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'group_id' => 'required|integer|exists:user_groups,id'
        ]);

        $userId = $request->user_id;
        $groupId = $request->group_id;
        
        // Check if user is in the group
        $userInGroup = DB::table('users')
            ->where('id', $userId)
            ->where('group_id', $groupId)
            ->exists();
        
        if (!$userInGroup) {
            return response()->json([
                'success' => false,
                'message' => 'User must be in the group to be set as leader'
            ], 400);
        }
        
        // Remove previous leader (if exists)
        DB::table('group_leaders')
            ->where('group_id', $groupId)
            ->delete();
        
        // Set new leader
        DB::table('group_leaders')->insert([
            'group_id' => $groupId,
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User set as group leader successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to set leader: ' . $e->getMessage()
        ], 500);
    }
});

// Remove user as group leader
Route::post('/users/group/remove-leader', function (Request $request) {
    try {
        $request->validate([
            'user_id' => 'required|integer',
            'group_id' => 'required|integer'
        ]);

        $userId = $request->user_id;
        $groupId = $request->group_id;
        
        // Remove leader
        DB::table('group_leaders')
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leader removed successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to remove leader: ' . $e->getMessage()
        ], 500);
    }
});

// Logout
Route::post('/logout', function (Request $request) {
    $request->session()->flush();
    return redirect('/login');
})->name('logout');
