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
                    ->reactive(), 
                Forms\Components\DatePicker::make('selesai')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->after('mulai') 
                    ->rule(function (callable $get) {
                        $mulai = $get('mulai');
                        return 'after:' . $mulai;
                    }),
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
