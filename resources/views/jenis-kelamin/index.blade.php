@extends('layouts.app')

@section('title', 'Data Jenis Kelamin')

@section('content')
    <!-- Page Header -->
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <span style="text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.2px; color: var(--accent-green); display: block; margin-bottom: 4px;">Data Master</span>
            <h2 style="font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">Jenis Kelamin</h2>
        </div>
        <div>
            <span style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); padding: 8px 16px; border-radius: 10px; font-size: 13px; color: var(--text-muted); font-weight: 600;">
                🔒 Read-Only
            </span>
        </div>
    </div>

    <!-- Table Container -->
    <div style="background: var(--glass-bg); backdrop-filter: blur(16px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; width: 80px;">NO</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; width: 120px;">KODE</th>
                    <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jenisKelamin as $index => $jk)
                    <tr style="border-bottom: 1px solid var(--glass-border);">
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--accent-green);">
                            #{{ $index + 1 }}
                        </td>
                        <td style="padding: 18px 24px; font-weight: 700; color: var(--text-main);">
                            <span style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 4px 12px; border-radius: 6px;">
                                {{ $jk->kode }}
                            </span>
                        </td>
                        <td style="padding: 18px 24px; font-weight: 600; color: var(--text-main);">
                            {{ $jk->nama }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 48px 24px; text-align: center; color: var(--text-muted);">
                            <p style="margin: 0; font-size: 15px;">Data jenis kelamin tidak ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection