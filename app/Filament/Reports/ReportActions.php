<?php

namespace App\Filament\Reports;

use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Exporter;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReportActions
{
    /**
     * Crea el grupo de acciones "Reportes" (PDF, Excel y CSV) para el encabezado de una tabla.
     *
     * @param  class-string<Exporter>  $exporter  Exportador usado para Excel y CSV.
     * @param  array<string, Closure(Model): mixed>  $columns  Encabezado => valor de cada fila del PDF.
     * @param  array<int, string>  $with  Relaciones a precargar al generar el PDF.
     */
    public static function make(
        string $exporter,
        string $title,
        array $columns,
        array $with = [],
        string $view = 'reports.report',
    ): ActionGroup {
        return ActionGroup::make([
            self::pdfAction($title, $columns, $with, $view),
            self::exportAction($exporter, ExportFormat::Xlsx, 'excel', 'Excel', Heroicon::TableCells),
            self::exportAction($exporter, ExportFormat::Csv, 'csv', 'CSV', Heroicon::DocumentText),
        ])
            ->label('Reportes')
            ->icon(Heroicon::ArrowDownTray)
            ->button()
            ->color('gray');
    }

    /**
     * @param  array<string, Closure(Model): mixed>  $columns
     * @param  array<int, string>  $with
     */
    protected static function pdfAction(string $title, array $columns, array $with, string $view): Action
    {
        return Action::make('pdf')
            ->label('PDF')
            ->icon(Heroicon::DocumentArrowDown)
            ->action(function (HasTable $livewire) use ($title, $columns, $with, $view): StreamedResponse {
                $query = $livewire->getTableQueryForExport();

                if ($with !== []) {
                    $query->with($with);
                }

                $headings = array_keys($columns);
                $resolvers = array_values($columns);

                $rows = $query->get()
                    ->map(fn (Model $record): array => array_map(
                        fn (Closure $resolve): mixed => $resolve($record),
                        $resolvers,
                    ))
                    ->all();

                $pdf = Pdf::loadView($view, [
                    'title' => $title,
                    'headings' => $headings,
                    'rows' => $rows,
                    'generatedAt' => now(),
                    'generatedBy' => auth()->user()?->name,
                ])
                    ->setPaper('a4', 'landscape')
                    ->setOption('defaultFont', 'DejaVu Sans');

                return response()->streamDownload(
                    function () use ($pdf): void {
                        echo $pdf->output();
                    },
                    Str::slug($title).'-'.now()->format('Y-m-d').'.pdf',
                    ['Content-Type' => 'application/pdf'],
                );
            });
    }

    /**
     * @param  class-string<Exporter>  $exporter
     */
    protected static function exportAction(
        string $exporter,
        ExportFormat $format,
        string $name,
        string $label,
        Heroicon $icon,
    ): ExportAction {
        return ExportAction::make($name)
            ->label($label)
            ->icon($icon)
            ->exporter($exporter)
            ->formats([$format])
            ->columnMapping(false)
            ->modalHidden();
    }
}
