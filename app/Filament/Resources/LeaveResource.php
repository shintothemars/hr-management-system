<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'nama') // Pastikan 'nama' adalah kolom di tabel employees
                    ->required(),
                Select::make('type')
                    ->label('Jenis Cuti')
                    ->options([
                        'annual' => 'Tahunan',
                        'special' => 'Khusus',
                    ])
                    ->required(),
                    DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        if ($get('end_date')) {
                            $days = Carbon::parse($state)->diffInDays($get('end_date')) + 1;
                            $set('days', $days);
                        }
                    }),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $state, $get) {
                        if ($get('start_date')) {
                            $days = Carbon::parse($get('start_date'))->diffInDays($state) + 1;
                            $set('days', $days);
                        }
                    }),
                Forms\Components\TextInput::make('days')
                    ->label('Jumlah Hari')
                    ->hidden()
                    ->default(0),
                Textarea::make('reason')
                    ->label('Alasan Cuti')
                    ->required(),
                FileUpload::make('proof_path')
                    ->label('Upload Bukti')
                    ->directory('leave-proofs')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(2048) // 2MB
                    ->downloadable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nama') // Menggunakan relasi employee.nama
                    ->label('Karyawan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employee.section')
                    ->label('Section')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employee.departemen.nama') // Menggunakan relasi employee.departemen.nama
                    ->label('Departemen')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('days')
                    ->label('Hari'),
                TextColumn::make('start_date')
                    ->label('Tanggal')
                    ->formatStateUsing(fn ($record) => $record->start_date . ' s/d ' . $record->end_date),
                IconColumn::make('proof_path')
                    ->label('Bukti')
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved_by_hr' => 'warning',
                        'approved_by_manager' => 'primary',
                        'approved_by_leader' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Action::make('approve_hr')
                    ->button()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved_by_hr']);
                    }),
                Action::make('approve_manager')
                    ->button()
                    ->visible(fn ($record) => $record->status === 'approved_by_hr')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved_by_manager']);
                    }),
                Action::make('approve_leader')
                    ->button()
                    ->visible(fn ($record) => $record->status === 'approved_by_manager')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved_by_leader']);
                    }),
                Action::make('reject')
                    ->button()
                    ->color('danger')
                    ->form([
                        Textarea::make('notes')->label('Alasan Penolakan'),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'status' => 'rejected',
                            'notes' => $data['notes'],
                        ]);
                    }),
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
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}