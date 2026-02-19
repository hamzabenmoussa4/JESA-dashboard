{{-- resources/views/exports/minutes-selected-pdf.blade.php --}}
@php
  /** @var \Illuminate\Support\Collection|\App\Models\Minute[] $records */
  $generatedAt = $generatedAt ?? now();
  $userName = $user?->name ?? 'User';
@endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Minutes Export</title>
  <style>
    @page { margin: 24mm 18mm; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color:#111; font-size:12px; }
    h1,h2,h3{ margin:0 0 6px; }
    .header { text-align:center; margin-bottom:16px; }
    .meta { font-size:11px; color:#444; margin-top:2px; }
    .record { page-break-inside: avoid; margin-bottom:18px; padding-bottom:12px; border-bottom:1px solid #ddd; }
    .label { font-weight:bold; color:#222; }
    .block { margin-top:6px; white-space: pre-wrap; line-height:1.5; }
    .muted { color:#666; }
    .small { font-size:11px; }
    .table { width:100%; border-collapse: collapse; }
    .table th, .table td { padding:6px 8px; border:1px solid #e5e7eb; text-align:left; vertical-align: top; }
    .title { font-size:14px; font-weight:700; }
  </style>
</head>
<body>
  <div class="header">
    <h1>Minutes (Selected)</h1>
    <div class="meta">Generated: {{ $generatedAt->format('Y-m-d H:i') }} — {{ $userName }}</div>
  </div>

  @forelse($records as $r)
    <div class="record">
      <div class="title">{{ $r->meeting->meeting_name ?? '—' }}</div>
      <table class="table" width="100%">
        <tr>
          <th style="width: 28%;">Responsible</th>
          <td>{{ $r->responsible }}</td>
        </tr>
        <tr>
          <th>Due Date</th>
<td>{{ optional($r->due_date)->format('Y-m-d') }}</td>
        </tr>
        <tr>
          <th>Created At</th>
<td>{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
        </tr>
      </table>

      <div class="label" style="margin-top:10px;">Topic / Idea / Decision</div>
      {{-- ✅ TEXTE COMPLET, préserve les retours à la ligne --}}
      <div class="block">{{ $r->topic_idea_decision }}</div>
    </div>
  @empty
    <p class="muted">No records selected.</p>
  @endforelse
</body>
</html>
