{{--
    ============================================================================
    PLANTILLA EDITABLE DEL REPORTE PDF
    ============================================================================
    Este archivo controla el diseño de todos los reportes en PDF del sistema.
    Puedes editarlo libremente para:

      · Cambiar el logo y los datos del encabezado.
      · Agregar el nombre de la institución, departamento, dirección, etc.
      · Ajustar colores, tipografía, márgenes y el pie de página.

    Variables disponibles:
      $title        Título del reporte (por ejemplo "Activos TI").
      $headings     Arreglo con las etiquetas de las columnas.
      $rows         Arreglo de filas; cada fila es un arreglo de valores.
      $generatedAt  Fecha y hora de generación (Carbon).
      $generatedBy  Nombre del usuario que generó el reporte.

    Nota: el motor PDF (dompdf) no soporta todo el CSS moderno. Se recomienda
    usar tablas y estilos sencillos, como los que se muestran en este archivo.
    ============================================================================
--}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /* --------------------------------------------------------------------
           Datos institucionales del encabezado (edítalos aquí).
           -------------------------------------------------------------------- */
        @page {
            margin: 18mm 10mm 20mm 10mm;
        }

        * {
            box-sizing: border-box;
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            margin: 0;
            color: #1e293b;
            font-size: 9px;
            line-height: 1.35;
        }

        /* --------------------------------------------------------------------
           Encabezado: logo + título del reporte.
           -------------------------------------------------------------------- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .header-logo {
            width: 22%;
        }

        .header-logo img {
            max-height: 58px;
            max-width: 150px;
        }

        .header-titles {
            width: 78%;
            text-align: right;
        }

        .institution {
            color: #64748b;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        h1 {
            margin: 3px 0 0;
            color: #243652;
            font-size: 18px;
            font-weight: bold;
        }

        .accent-bar {
            margin: 10px 0 12px;
            height: 3px;
            background-color: #243652;
        }

        /* --------------------------------------------------------------------
           Ficha de datos del reporte (fecha, usuario, total de registros).
           -------------------------------------------------------------------- */
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .meta-table td {
            width: 33.33%;
            padding: 6px 8px;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
        }

        .meta-label {
            display: block;
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .meta-value {
            display: block;
            margin-top: 2px;
            color: #0f172a;
            font-size: 9px;
            font-weight: bold;
        }

        /* --------------------------------------------------------------------
           Tabla de datos.
           -------------------------------------------------------------------- */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data thead {
            display: table-header-group; /* Repite el encabezado en cada página. */
        }

        table.data th {
            padding: 6px 5px;
            background-color: #243652;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid #243652;
        }

        table.data td {
            padding: 5px;
            border: 1px solid #e2e8f0;
            font-size: 8.5px;
            vertical-align: top;
        }

        table.data tbody tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        table.data tr {
            page-break-inside: avoid;
        }

        table.data .index {
            width: 28px;
            color: #64748b;
            text-align: center;
        }

        table.data .empty {
            padding: 18px;
            color: #64748b;
            text-align: center;
            font-style: italic;
        }

        /* --------------------------------------------------------------------
           Pie de página (se repite en todas las páginas).
           -------------------------------------------------------------------- */
        .report-footer {
            position: fixed;
            bottom: -12mm;
            left: 0;
            right: 0;
            padding-top: 4px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 7.5px;
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        // Cambia esta ruta si usas otro logo (el archivo debe estar dentro de /public).
        $logoFile = public_path('images/inbolLogo.jpg');
        $logo = is_readable($logoFile)
            ? 'data:image/jpeg;base64,'.base64_encode(file_get_contents($logoFile))
            : null;

        $institution = 'INBOL';
    @endphp

    {{-- ====================================================================
         ENCABEZADO DEL REPORTE (logo, institución y título)
         ==================================================================== --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if ($logo)
                    <img src="{{ $logo }}" alt="Logo">
                @endif
            </td>
            <td class="header-titles">
                <div class="institution">{{ $institution }}</div>
                <h1>{{ $title }}</h1>
            </td>
        </tr>
    </table>

    <div class="accent-bar"></div>

    {{-- ====================================================================
         FICHA DEL REPORTE
         ==================================================================== --}}
    <table class="meta-table">
        <tr>
            <td>
                <span class="meta-label">Generado</span>
                <span class="meta-value">{{ $generatedAt->format('d/m/Y H:i') }}</span>
            </td>
            <td>
                <span class="meta-label">Generado por</span>
                <span class="meta-value">{{ $generatedBy ?? 'Sistema' }}</span>
            </td>
            <td>
                <span class="meta-label">Total de registros</span>
                <span class="meta-value">{{ count($rows) }}</span>
            </td>
        </tr>
    </table>

    {{-- ====================================================================
         TABLA DE DATOS
         ==================================================================== --}}
    <table class="data">
        <thead>
            <tr>
                <th class="index">N.º</th>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $index => $row)
                <tr>
                    <td class="index">{{ $index + 1 }}</td>
                    @foreach ($row as $value)
                        <td>{{ filled($value) ? $value : '—' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ count($headings) + 1 }}">
                        No hay registros para los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ====================================================================
         PIE DE PÁGINA
         ==================================================================== --}}
    <div class="report-footer">
        {{ $institution }} · {{ $title }} · Documento generado automáticamente el
        {{ $generatedAt->format('d/m/Y H:i') }}
    </div>
</body>

</html>
