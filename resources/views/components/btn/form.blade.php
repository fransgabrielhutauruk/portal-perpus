{{-- author : mwy --}}
@props([
    'link' => '',
    'action' => '',
    'class' => '',
    'icon' => '',
    'title' => '',
    'text' => '',
    'size' => 'sm',
])

@if ($action == 'save')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-{{ $size }} btn-primary {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'simpan data' }}">
        <i class="{{ $icon != '' ? $icon : 'bi bi-check2-circle' }} fs-3"></i> {{ $text != '' ? $text : 'Simpan data' }}
    </a>
@elseif ($action == 'edit')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-{{ $size }} btn-warning {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'edit form' }}">
        <i class="{{ $icon != '' ? $icon : 'bi bi-pencil' }} fs-3"></i> {{ $text != '' ? $text : 'Edit Data' }}
    </a>
@elseif ($action == 'reset')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-{{ $size }} btn-secondary {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'reset form' }}">
        <i class="{{ $icon != '' ? $icon : 'bi bi-arrow-clockwise' }} fs-3"></i> {{ $text != '' ? $text : 'Reset' }}
    </a>
@elseif ($action == 'cancle')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-{{ $size }} btn-danger {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'reset form' }}">
        <i class="{{ $icon != '' ? $icon : 'bi bi-x-circle' }} fs-3"></i> {{ $text != '' ? $text : 'Cancle' }}
    </a>
@elseif ($action == 'approve')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-{{ $size }} btn-success {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'approve data' }}">
        <i class="{{ $icon != '' ? $icon : 'bi bi-check-circle' }} fs-3"></i> {{ $text != '' ? $text : 'Approve' }}
    </a>
@elseif ($action == 'reject')
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-{{ $size }} btn-danger {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'reject data' }}">
        <i class="{{ $icon != '' ? $icon : 'bi bi-x-circle' }} fs-3"></i> {{ $text != '' ? $text : 'Reject' }}
    </a>
@else
    <a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-{{ $size }} btn-light {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : '' }}">
        <i class="{{ $icon != '' ? $icon : 'bi bi-list' }} fs-3"></i>
    </a>
@endif
