<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Foto Profil
                Forms\Components\Section::make('Foto Profil')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_profil')
                            ->image()
                            ->directory('employee-photos')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth(300)
                            ->imageResizeTargetHeight(300)
                            ->previewable(true)
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])->collapsible(),
                // Section 2: Informasi Pribadi
                Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nama_panggilan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->required(),
                        Forms\Components\TextInput::make('alamat_identitas')
                            ->required(),
                        Forms\Components\TextInput::make('alamat_domisili')
                            ->required(),
                            Forms\Components\TextInput::make('no_telepon_rumah')
                            ->label('No. Telepon Rumah')
                            ->tel(),
                        
                        Forms\Components\Select::make('status_keluarga')
                            ->options([
                                'lajang' => 'Lajang',
                                'menikah' => 'Menikah',
                                'duda' => 'Duda',
                                'janda' => 'Janda'
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('jumlah_anak')
                            ->numeric()
                            ->minValue(0),
                        
                        Forms\Components\TextInput::make('tinggi_badan')
                            ->suffix('cm'),
                        
                        Forms\Components\TextInput::make('berat_badan')
                            ->suffix('kg'),
                        
                        Forms\Components\TextInput::make('no_ktp')
                            ->label('No. KTP'),
                        
                        Forms\Components\TextInput::make('masa_berlaku_ktp')
                            ->label('Masa Berlaku KTP')
                            ->placeholder('Seumur hidup'),
                        Forms\Components\Select::make('golongan_darah')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O'
                            ])
                            ->required(),
                        Forms\Components\Select::make('agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Konghucu' => 'Konghucu'
                            ])
                            ->required(),
                        ])->columns(2),
                        // Section 3: Data Pekerjaan
                Forms\Components\Section::make('Data Pekerjaan')
                ->schema([
                    Forms\Components\TextInput::make('jabatan')
                        ->required(),
                        
                    Forms\Components\Select::make('departemen_id')
                        ->label('Departemen')
                        ->relationship('departemen', 'nama') // Pastikan nama relasi sama
                        ->required()
                        ->preload()
                        ->searchable()
                        ->createOptionForm([ // Opsi untuk membuat departemen baru langsung dari form
                            Forms\Components\TextInput::make('nama')
                                ->required(),
                            Forms\Components\Textarea::make('deskripsi'),
                        ]),
                ])
                
        

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto_profil')
                     ->label('Foto Profil')
                     ->circular(),
                //     ->url(fn ($record) => $record->foto_profil ? asset('storage/' . $record->foto_profil) : null)
                //     ->defaultImageUrl(asset('images/default-profile.png')), // Opsional: jika tidak ada foto

                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('jabatan'),
                
                Tables\Columns\TextColumn::make('departemen.nama'),
                
                Tables\Columns\TextColumn::make('email'),
            ])
              
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\EducationsRelationManager::class,
            RelationManagers\FamiliesRelationManager::class,
            RelationManagers\WorkExperiencesRelationManager::class,
            RelationManagers\ReferencesRelationManager::class,
            RelationManagers\EmployeeDocumentRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
