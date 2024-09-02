@if ($getRecord()->attachments)
    <a
        href="{{ Storage::url($getRecord()->attachments) }}"
        class="filament-button filament-button-primary"
        download
    >
        Télécharger le fichier
    </a>
@endif
