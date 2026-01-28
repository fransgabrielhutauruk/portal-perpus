{{-- author : mwy --}}
@props([
    'link' => '',
    'action' => '',
    'class' => '',
    'icon' => '',
    'title' => '',
])

@if ($action == 'edit')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-warning act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Edit data' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-notepad-edit' }} fs-3"></i>
    </a>
@elseif ($action == 'delete')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Hapus data' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-trash' }} fs-3"></i>
    </a>
@elseif ($action == 'detail')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-primary act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Detail data' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-eye' }} fs-3"></i>
    </a>
@elseif ($action == 'approve' || $action == 'approve')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-success act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Setujui' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-check-circle' }} fs-3"></i>
    </a>
@elseif ($action == 'reject' || $action == 'reject')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Tolak' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-cross-circle' }} fs-3"></i>
    </a>
@elseif ($action == 'download')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-info act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Download File' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-cloud-download' }} fs-3"></i>
    </a>
@elseif ($action == 'detail')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-primary act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Detail data' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-magnifier' }} fs-3"></i>
    </a>
@else
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm mh-25px mw-25px btn-secondary act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'Lainnya' }}">
        <i class="{{ $icon != '' ? $icon : 'ki-outline ki-burger-menu-6 text-gray-500' }} fs-3"></i>
    </a>
@endif
