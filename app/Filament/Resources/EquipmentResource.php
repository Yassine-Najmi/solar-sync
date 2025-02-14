<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Filament\Resources\EquipmentResource\RelationManagers;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('type')
                            ->options([
                                'panel' => 'Solar Panel',
                                'inverter' => 'Inverter',
                                'battery' => 'Battery',
                                'mounting' => 'Mounting System',
                            ])
                            ->live()
                            ->required(),
                        TextInput::make('model')
                            ->required(),
                        TextInput::make('manufacturer')
                            ->required(),
                        TextInput::make('power_rating')
                            ->numeric()
                            ->suffix('kW')
                            ->required(),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        TextInput::make('stock_quantity')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])->columns(2),

                    Section::make('Specifications')
                    ->schema([
                        Repeater::make('specifications')
                            ->schema([
                                Select::make('key')
                                    ->options(function (Get $get) {
                                        $type = $get('../../type');
                                        return match ($type) {
                                            'panel' => [
                                                'power_output' => 'Power Output (W)',
                                                'efficiency' => 'Efficiency (%)',
                                                'dimensions' => 'Dimensions (mm)',
                                                'weight' => 'Weight (kg)',
                                                'cell_type' => 'Cell Type',
                                                'warranty_years' => 'Warranty (Years)',
                                                'temperature_coefficient' => 'Temperature Coefficient (%/°C)',
                                                'operating_temperature' => 'Operating Temperature Range',
                                            ],
                                            'inverter' => [
                                                'max_power' => 'Maximum Power (W)',
                                                'efficiency' => 'Efficiency (%)',
                                                'input_voltage_range' => 'Input Voltage Range',
                                                'max_output_current' => 'Max Output Current (A)',
                                                'number_of_mppt' => 'Number of MPPT',
                                                'warranty_years' => 'Warranty (Years)',
                                                'communication' => 'Communication Protocol',
                                                'ip_rating' => 'IP Rating',
                                            ],
                                            'battery' => [
                                                'capacity' => 'Capacity (kWh)',
                                                'usable_capacity' => 'Usable Capacity (kWh)',
                                                'max_power' => 'Maximum Power (kW)',
                                                'round_trip_efficiency' => 'Round Trip Efficiency (%)',
                                                'warranty_years' => 'Warranty (Years)',
                                                'chemistry' => 'Battery Chemistry',
                                                'operating_temperature' => 'Operating Temperature Range',
                                                'dimensions' => 'Dimensions (mm)',
                                            ],
                                            'mounting' => [
                                                'material' => 'Material',
                                                'roof_type_compatibility' => 'Compatible Roof Types',
                                                'wind_rating' => 'Wind Rating',
                                                'warranty_years' => 'Warranty (Years)',
                                                'corrosion_resistance' => 'Corrosion Resistance',
                                                'tilt_angle_range' => 'Tilt Angle Range',
                                            ],
                                            default => [
                                                'warranty_years' => 'Warranty (Years)',
                                                'dimensions' => 'Dimensions',
                                                'weight' => 'Weight',
                                                'material' => 'Material',
                                            ],
                                        };
                                    })
                                    ->required()
                                    ->searchable()
                                    ->live(),
                                TextInput::make('value')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable(true)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['key'] ?? null)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('manufacturer')
                    ->searchable(),
                TextColumn::make('power_rating')
                    ->suffix(' kW')
                    ->numeric(2),
                TextColumn::make('unit_price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->numeric()
                    ->sortable()
                    ->color(
                        fn(Equipment $record): string =>
                        $record->stock_quantity < 5 ? 'danger' : 'success'
                    ),
            ])
            ->filters([
                SelectFilter::make('type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
