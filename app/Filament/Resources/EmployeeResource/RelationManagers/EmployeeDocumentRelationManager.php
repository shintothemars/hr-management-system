<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeDocumentRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeDocuments';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Employee Document';

    protected static ?string $navigationGroup = 'Employee Management';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Document Information')
                ->schema([
                    Forms\Components\Select::make('employee_id')
                        ->relationship('employee', 'nama')
                        ->label('Employee')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('title')
                        ->label('Document Title')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('type')
                        ->label('Document Type')
                        ->options([
                            'contract' => 'Employment Contract',
                            'id' => 'ID/Passport',
                            'certificate' => 'Certificate',
                            'other' => 'Other'
                        ])
                        ->required(),  
                        Forms\Components\FileUpload::make('file_path')
                        ->label('Document File')
                        ->directory('employee_documents')
                        ->preserveFilenames()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                            'application/msword',
                        ])
                        ->downloadable()
                        ->openable(),

                    Forms\Components\Textarea::make('notes')
                        ->label('Additional Notes')
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee')
            ->columns([
            Tables\Columns\TextColumn::make('employee.nama')
                ->label('Employee Name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('title')
                ->label('Title')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->label('Type')
                ->sortable()
                ->searchable(),
                
            Tables\Columns\IconColumn::make('file_path')
                    ->label('File')
                    ->options([
                        'heroicon-o-document-text' => fn ($state) => $state !== null, // Tampilkan ikon jika file ada
                    ])
                    ->url(fn ($record) => $record->file_path ? asset('storage/' . $record->file_path) : null, true) // Tautkan ke file
                    ->openUrlInNewTab() // Buka di tab baru
                    ->tooltip(fn ($state) => $state ? 'View Document' : 'No File'), // Tooltip
            Tables\Columns\TextColumn::make('notes')
                ->label('Notes')
                ->limit(50),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Uploaded At')
                ->dateTime('d M Y H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('employee_id')
                    ->relationship('employee', 'nama')
                    ->label('Employee'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make() // Tambahkan aksi View
                ->label('Lihat Detail'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
