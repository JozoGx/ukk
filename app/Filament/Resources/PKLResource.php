<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PklResource\Pages;
use App\Models\Pkl;
use App\Models\Siswa;
use App\Models\Industri;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PklResource extends Resource
{
    protected static ?string $model = Pkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'PKL';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('siswa_id')
                    ->label('Siswa')
                    ->relationship('siswa', 'nama')
                    ->required(),
                Forms\Components\Select::make('industri_id')
                    ->label('Industri')
                    ->relationship('industri', 'nama')
                    ->required(),
                Forms\Components\Select::make('guru_id')
                    ->label('Guru Pembimbing')
                    ->relationship('guru', 'nama')
                    ->required(),
                Forms\Components\DatePicker::make('mulai')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        // Auto-set tanggal selesai menjadi 90 hari setelah tanggal mulai
                        if ($state) {
                            $mulai = Carbon::parse($state);
                            $selesaiOtomatis = $mulai->addDays(90)->format('Y-m-d');
                            $set('selesai', $selesaiOtomatis);
                        }
                    }), 
                Forms\Components\DatePicker::make('selesai')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->after('mulai')
                    ->reactive()
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                $mulai = request()->input('data.mulai') ?? request()->input('mulai');
                                
                                if ($mulai && $value) {
                                    $mulaiDate = Carbon::parse($mulai);
                                    $selesaiDate = Carbon::parse($value);
                                    $diffInDays = $mulaiDate->diffInDays($selesaiDate);
                                    
                                    if ($diffInDays < 90) {
                                        $fail("Durasi PKL minimal 90 hari. Durasi yang dipilih: {$diffInDays} hari.");
                                    }
                                }
                            };
                        }
                    ])
                    ->helperText('Minimal 90 hari dari tanggal mulai'),
                
                // Tambahkan komponen untuk menampilkan durasi
                Forms\Components\Placeholder::make('durasi_info')
                    ->label('Informasi Durasi')
                    ->content(function (Forms\Get $get): string {
                        $mulai = $get('mulai');
                        $selesai = $get('selesai');
                        
                        if ($mulai && $selesai) {
                            $mulaiDate = Carbon::parse($mulai);
                            $selesaiDate = Carbon::parse($selesai);
                            $diffInDays = $mulaiDate->diffInDays($selesaiDate);
                            
                            $status = $diffInDays >= 90 ? '✅ Valid' : '❌ Tidak Valid';
                            $color = $diffInDays >= 90 ? 'success' : 'danger';
                            
                            return "Durasi: {$diffInDays} hari - {$status}";
                        }
                        
                        return 'Pilih tanggal mulai dan selesai untuk melihat durasi';
                    })
                    ->reactive()
                    ->columnSpanFull(),
            ]);
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        if (!$user->hasRole('siswa')) {
            return true;
        }

        return !\App\Models\Pkl::where('siswa_id', $user->siswa->id)->exists();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama')->label('Siswa')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('industri.nama')->label('Industri')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('guru.nama')->label('Guru Pembimbing')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mulai')->label('Tanggal Mulai')->date()->sortable(),
                Tables\Columns\TextColumn::make('selesai')->label('Tanggal Selesai')->date()->sortable(),
                // Tambahkan kolom durasi
                Tables\Columns\TextColumn::make('durasi')
                    ->label('Durasi (Hari)')
                    ->getStateUsing(function ($record): string {
                        $mulai = Carbon::parse($record->mulai);
                        $selesai = Carbon::parse($record->selesai);
                        $diffInDays = $mulai->diffInDays($selesai);
                        return $diffInDays . ' hari';
                    })
                    ->badge()
                    ->color(fn ($record): string => 
                        Carbon::parse($record->mulai)->diffInDays(Carbon::parse($record->selesai)) >= 90 
                            ? 'success' 
                            : 'danger'
                    ),
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
            'index' => Pages\ListPkls::route('/'),
            'create' => Pages\CreatePkl::route('/create'),
            'edit' => Pages\EditPkl::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Lapor PKL';
    }
}