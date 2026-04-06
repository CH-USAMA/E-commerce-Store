@extends('layouts.admin')

@section('title', 'Theme Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-transparent border-bottom border-default py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-black gradient-title"><i class="fas fa-palette me-2"></i> Theme Customization</h4>
                        <p class="text-muted small mb-0">Personalize your brand identity across the entire system.</p>
                    </div>
                    <form action="{{ route('admin.settings.theme.update') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset the theme to default? All custom colors will be removed.')">
                        @csrf
                        <input type="hidden" name="reset" value="1">
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill border-0 bg-danger bg-opacity-10">
                            <i class="fas fa-undo me-1"></i> Reset to Carbon & Gold
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body p-5">
                <form action="{{ route('admin.settings.theme.update') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label class="form-label d-block h6 fw-bold text-white mb-2">Primary Brand Color</label>
                            <p class="text-muted small mb-3">Accent color for buttons & active states.</p>
                            
                            <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-black bg-opacity-20 border border-default">
                                <input type="color" name="theme_primary_color" 
                                       value="{{ $settings['theme_primary_color'] ?? '#FF8C00' }}" 
                                       class="form-control form-control-color border-0 bg-transparent p-0" 
                                       style="width: 60px; height: 60px; cursor: pointer; border-radius: 8px;"
                                       id="primaryColor">
                                <div class="flex-grow-1">
                                    <p class="text-white font-monospace mb-0" id="hexDisplayPrimary">{{ strtoupper($settings['theme_primary_color'] ?? '#FF8C00') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label d-block h6 fw-bold text-white mb-2">Page Background</label>
                            <p class="text-muted small mb-3">The main body background color.</p>
                            
                            <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-black bg-opacity-20 border border-default">
                                <input type="color" name="theme_background_color" 
                                       value="{{ $settings['theme_background_color'] ?? '#0a0a0a' }}" 
                                       class="form-control form-control-color border-0 bg-transparent p-0" 
                                       style="width: 60px; height: 60px; cursor: pointer; border-radius: 8px;"
                                       id="backgroundColor">
                                <div class="flex-grow-1">
                                    <p class="text-white font-monospace mb-0" id="hexDisplayBackground">{{ strtoupper($settings['theme_background_color'] ?? '#0a0a0a') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label d-block h6 fw-bold text-white mb-2">Surface Color</label>
                            <p class="text-muted small mb-3">Used for cards, sidebars, and panels.</p>
                            
                            <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-black bg-opacity-20 border border-default">
                                <input type="color" name="theme_surface_color" 
                                       value="{{ $settings['theme_surface_color'] ?? '#111111' }}" 
                                       class="form-control form-control-color border-0 bg-transparent p-0" 
                                       style="width: 60px; height: 60px; cursor: pointer; border-radius: 8px;"
                                       id="surfaceColor">
                                <div class="flex-grow-1">
                                    <p class="text-white font-monospace mb-0" id="hexDisplaySurface">{{ strtoupper($settings['theme_surface_color'] ?? '#111111') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-12 ps-md-0 mt-4 mt-md-0">
                            <label class="form-label d-block h6 fw-bold text-white mb-2">Interface Preview</label>
                            <div class="p-5 rounded-4 border border-default position-relative overflow-hidden shadow-2xl transition-all duration-500" 
                                 id="previewContainer"
                                 style="background-color: {{ $settings['theme_background_color'] ?? '#0a0a0a' }};">
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- Mock Sidebar -->
                                        <div class="p-3 rounded-4 shadow-sm h-100" 
                                             id="previewSurface"
                                             style="background-color: {{ $settings['theme_surface_color'] ?? '#111111' }}; border: 1px solid rgba(255,255,255,0.05);">
                                            
                                            <div class="px-3 py-2 rounded-3 mb-2 d-flex align-items-center gap-3" 
                                                 id="previewActiveItem"
                                                 style="background: linear-gradient(90deg, {{ ($settings['theme_primary_color'] ?? '#FF8C00') }}33, {{ ($settings['theme_primary_color'] ?? '#FF8C00') }}14); border-left: 3px solid {{ $settings['theme_primary_color'] ?? '#FF8C00' }};">
                                                <i class="fas fa-tachometer-alt" style="color: {{ $settings['theme_primary_color'] ?? '#FF8C00' }}; font-size: 10px;"></i>
                                                <span class="fw-bold" style="font-size: 9px; color: {{ $settings['theme_text_color'] ?? '#ffffff' }};">Sidebar Item</span>
                                            </div>
                                            
                                            @for($i=0; $i<3; $i++)
                                            <div class="px-3 py-2 d-flex align-items-center gap-3 mb-1">
                                                <i class="fas fa-circle text-muted" style="font-size: 6px;"></i>
                                                <div class="bg-secondary bg-opacity-20 rounded-pill" style="width: 60%; height: 6px;"></div>
                                            </div>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="mb-4 fw-bold" id="previewHeading" style="color: {{ $settings['theme_text_color'] ?? '#ffffff' }};">Main Dashboard Content</h5>
                                        
                                        <div class="row g-3">
                                            @for($i=0; $i<2; $i++)
                                            <div class="col-6">
                                                <div class="p-4 rounded-4 shadow-sm" style="background-color: {{ $settings['theme_surface_color'] ?? '#111111' }}; border: 1px solid rgba(255,255,255,0.05);">
                                                    <div class="rounded-pill mb-2" style="width: 30%; height: 8px; background-color: {{ $settings['theme_primary_color'] ?? '#FF8C00' }};"></div>
                                                    <div class="bg-secondary bg-opacity-20 rounded-pill w-100 mb-1" style="height: 6px;"></div>
                                                    <div class="bg-secondary bg-opacity-20 rounded-pill w-75" style="height: 6px;"></div>
                                                </div>
                                            </div>
                                            @endfor
                                        </div>

                                        <div class="mt-4 d-flex gap-3">
                                            <button type="button" class="btn px-4 py-2 rounded-2 fw-black text-uppercase preview-btn shadow-lg" 
                                                    style="background: {{ $settings['theme_primary_color'] ?? '#FF8C00' }}; color: {{ $settings['theme_primary_text_color'] ?? '#ffffff' }}; border: none; font-size: 10px;">
                                                Action Button
                                            </button>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                                 id="previewIconBg"
                                                 style="width: 38px; height: 38px; background: {{ ($settings['theme_primary_color'] ?? '#FF8C00') }}26;">
                                                <i class="fas fa-shopping-bag preview-icon" style="color: {{ $settings['theme_primary_color'] ?? '#FF8C00' }}; font-size: 14px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top border-default pt-5 text-center">
                        <button type="submit" class="btn btn-jabulani px-5 py-3 rounded-pill shadow-lg hover-scale">
                            <i class="fas fa-check-circle me-2"></i> Implement Color Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.2s ease; }
    .hover-scale:hover { transform: scale(1.05); }
    #primaryColor:hover { transform: scale(1.05); }
</style>

@push('js')
<script>
    const primaryColorInput = document.getElementById('primaryColor');
    const backgroundColorInput = document.getElementById('backgroundColor');
    const surfaceColorInput = document.getElementById('surfaceColor');
    
    // Display elements
    const hexDisplayPrimary = document.getElementById('hexDisplayPrimary');
    const hexDisplayBackground = document.getElementById('hexDisplayBackground');
    const hexDisplaySurface = document.getElementById('hexDisplaySurface');
    
    // Preview Elements
    const previewContainer = document.getElementById('previewContainer');
    const previewSurface = document.getElementById('previewSurface');
    const previewActiveItem = document.getElementById('previewActiveItem');
    const previewHeading = document.getElementById('previewHeading');
    const previewBtn = document.querySelector('.preview-btn');
    const previewIcon = document.querySelector('.preview-icon');
    const previewIconBg = document.getElementById('previewIconBg');

    function updatePreview() {
        const primary = primaryColorInput.value;
        const bg = backgroundColorInput.value;
        const surface = surfaceColorInput.value;
        
        const primaryText = getContrastColor(primary);
        const bodyText = getContrastColor(bg);
        
        hexDisplayPrimary.textContent = primary.toUpperCase();
        hexDisplayBackground.textContent = bg.toUpperCase();
        hexDisplaySurface.textContent = surface.toUpperCase();
        
        // Update Container & Heading
        previewContainer.style.backgroundColor = bg;
        previewHeading.style.color = bodyText;
        
        // Update Surface elements
        previewSurface.style.backgroundColor = surface;
        
        // Update Primary elements
        previewBtn.style.backgroundColor = primary;
        previewBtn.style.color = primaryText;
        previewIcon.style.color = primary;
        previewIconBg.style.backgroundColor = primary + '26'; // 15% opacity hex
        
        previewActiveItem.style.borderLeftColor = primary;
        previewActiveItem.style.background = `linear-gradient(90deg, ${primary}33, ${primary}14)`;
    }

    primaryColorInput.addEventListener('input', updatePreview);
    backgroundColorInput.addEventListener('input', updatePreview);
    surfaceColorInput.addEventListener('input', updatePreview);

    function getContrastColor(hexColor) {
        const hex = hexColor.replace('#', '');
        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return (luminance > 0.6) ? '#000000' : '#ffffff';
    }
</script>
@endpush
@endsection
