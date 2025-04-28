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
    protected static string $relationship = 'EmployeeDocument';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Document Title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('type')
                        ->label('Document Type')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('file_path')
                        ->label('File')
                        ->directory('employee_documents')
                        ->required(),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notes')
                        ->nullable(),
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->label('Title')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->label('Type')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('file_path')
                ->label('File')
                ->formatStateUsing(fn ($state) => $state ? "<a href='" . asset('storage/' . $state) . "' target='_blank'>View</a>" : 'No File')
                ->html(), // Pastikan kolom mendukung HTML
            Tables\Columns\TextColumn::make('notes')
                ->label('Notes')
                ->limit(50),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Uploaded At')
                ->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
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
