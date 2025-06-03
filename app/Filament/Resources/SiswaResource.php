<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationGroup = 'Data';
    
    protected static ?string $navigationLabel = 'Data Siswa';
    
    protected static ?string $modelLabel = 'Siswa';
    
    protected static ?string $pluralModelLabel = 'Siswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->label('Foto Siswa')
                    ->image()
                    ->directory('siswa-photos')
                    ->disk('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                        '4:3',
                    ])
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('nis')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                    
                Forms\Components\Select::make('gender')
                    ->required()
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                    
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                    
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
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                    
                Forms\Components\Toggle::make('status_pkl')
                    ->label('Status PKL')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->height(50)
                    ->width(50)
                    ->circular()
                    ->getStateUsing(function ($record) {
                        if ($record->foto) {
                            return asset('storage/' . $record->foto);
                        }
                        return asset('storage/siswa-photos/default-avatar.png');
                    })
                    ->url(function ($record) {
                        if ($record->foto) {
                            return asset('storage/' . $record->foto);
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('gender')
                    ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                    
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
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('status_pkl')
                    ->label('Status PKL')
                    ->getStateUsing(fn ($record) => $record->pkl()->exists())
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                    
                Tables\Filters\TernaryFilter::make('status_pkl')
                    ->label('Status PKL')
                    ->queries(
                        true: fn ($query) => $query->whereHas('pkl'),
                        false: fn ($query) => $query->whereDoesntHave('pkl'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        // Check if student has active PKL
                        if ($record->pkl()->exists()) {
                            Notification::make()
                                ->title('Tidak dapat menghapus siswa!')
                                ->body('Siswa ini sedang mengikuti PKL. Hapus data PKL terlebih dahulu sebelum menghapus data siswa.')
                                ->danger()
                                ->send();
                            
                            // Cancel the delete action
                            $action->cancel();
                            return false;
                        }
                        return true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action, $records) {
                            // Check if any of the selected students have active PKL
                            $studentsWithPKL = $records->filter(function ($record) {
                                return $record->pkl()->exists();
                            });
                            
                            if ($studentsWithPKL->count() > 0) {
                                $studentNames = $studentsWithPKL->pluck('nama')->join(', ');
                                
                                Notification::make()
                                    ->title('Tidak dapat menghapus beberapa siswa!')
                                    ->body("Siswa berikut sedang mengikuti PKL dan tidak dapat dihapus: {$studentNames}. Hapus data PKL mereka terlebih dahulu.")
                                    ->danger()
                                    ->send();
                                
                                // Cancel the bulk delete action
                                $action->cancel();
                                return false;
                            }
                            return true;
                        }),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }

    // public static function canAccess(): bool
    // {
    //     return auth()->user()->hasRole('admin');
    // }
}