<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndustriResource\Pages;
use App\Models\Industri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Guru;

class IndustriResource extends Resource
{
    protected static ?string $model = Industri::class;

    protected static ?string $navigationIcon = 'heroicon-c-building-office-2';

    protected static ?string $navigationGroup = 'Data';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bidang_usaha')
                    ->label('Bidang Usaha')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->required(),
                Forms\Components\TextInput::make('kontak')
                    ->label('Kontak')
                    ->required()
                    ->tel()
                    ->prefix('+62 ')
                    ->placeholder('8123456789')
                    ->helperText('Masukkan nomor tanpa kode negara')
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bidang_usaha')
                    ->label('Bidang Usaha')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50),
                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor telepon disalin!')
                    ->icon('heroicon-o-phone')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        
                        // Jika nomor dimulai dengan 8, tambahkan +62 0
                        if (str_starts_with($state, '8')) {
                            return '0' . $state;
                        }
                        
                        // Jika sudah ada +62, ganti dengan +62 0
                        if (str_starts_with($state, '+62')) {
                            $number = substr($state, 3); // Ambil bagian setelah +62
                            $number = ltrim($number, ' '); // Hapus spasi
                            if (str_starts_with($number, '8')) {
                                return '0' . $number;
                            }
                        }
                        
                        return $state; // Return original jika format tidak sesuai
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListIndustris::route('/'),
            'create' => Pages\CreateIndustri::route('/create'),
            'edit' => Pages\EditIndustri::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Data Industri';
    }

    // public static function canAccess(): bool
    // {
    //     return auth()->user()->hasRole('admin');
    // }
}
