<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservaResource\Pages;
use App\Models\Reserva;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Resource al estilo Filament 3, el que más se ve en entrevistas y en empresas.
 * En Filament 4/5 form() y table() reciben Schema en vez de Form/Table;
 * los campos (TextInput, Select, TextColumn) son los mismos.
 */
class ReservaResource extends Resource
{
    protected static ?string $model = Reserva::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Reservas';

    protected static ?string $modelLabel = 'reserva';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('espacio_id')
                ->label('Espacio')
                ->relationship('espacio', 'nombre')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\DatePicker::make('fecha')
                ->required()
                ->minDate(now()->toDateString()),
            Forms\Components\Select::make('franja')
                ->options([
                    'manana' => 'Mañana',
                    'tarde' => 'Tarde',
                    'noche' => 'Noche',
                ])
                ->required(),
            Forms\Components\Select::make('estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'confirmada' => 'Confirmada',
                    'rechazada' => 'Rechazada',
                ])
                ->default('pendiente')
                ->required(),
            Forms\Components\Textarea::make('notas')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('espacio.nombre')
                    ->label('Espacio')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Residente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('franja')->badge(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmada' => 'success',
                        'rechazada' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                        'rechazada' => 'Rechazada',
                    ]),
                Tables\Filters\SelectFilter::make('espacio_id')
                    ->relationship('espacio', 'nombre')
                    ->label('Espacio'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        // Evita el N+1: la tabla no dispara una consulta por cada fila.
        return parent::getEloquentQuery()->with(['espacio', 'user']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservas::route('/'),
            'create' => Pages\CreateReserva::route('/create'),
            'edit' => Pages\EditReserva::route('/{record}/edit'),
        ];
    }
}
