<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-m-user';

    protected static ?string $navigationGroup = 'Data';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // Optional: Ubah warna badge
    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();
        
        // Contoh: hijau jika > 10, kuning jika 5-10, merah jika < 5
        if ($count > 10) {
            return 'success';
        } elseif ($count >= 5) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
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
                    ->maxLength(15)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
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
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                    ->sortable(),
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
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Data Guru';
    }
}
