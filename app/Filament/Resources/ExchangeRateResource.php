<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangeRateResource\Pages;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExchangeRateResource extends Resource
{
    protected static ?string $model = ExchangeRate::class;

    protected static ?string $navigationIcon = 'heroicon-c-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('exchange_rate_date')->format('d.m.Y')->disabled(),
                Forms\Components\Select::make('currency_id')->label('Currency')->options(Currency::all()->pluck('name', 'id'))->disabled(),
                Forms\Components\Select::make('bank_id')->label('Bank')->options(Bank::all()->pluck('name', 'id'))->disabled(),
                Forms\Components\TextInput::make('value')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('currency.name'),
                TextColumn::make('exchange_rate_date')->date('d.m.Y')->sortable(),
                TextColumn::make('bank.name'),
                TextColumn::make('value'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency_id')
                    ->label('Currency')
                    ->options(Currency::whereEnabled(true)->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('bank_id')
                    ->label('Bank')
                    ->options(Bank::whereEnabled(true)->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListExchangeRates::route('/'),
            //            'create' => Pages\CreateExchangeRate::route('/create'),
            'edit' => Pages\EditExchangeRate::route('/{record}/edit'),
        ];
    }
}
