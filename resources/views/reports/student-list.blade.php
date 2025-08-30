<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alumnos</title>
    <style>
        /* Estilos generales para el PDF */
        @page { margin: 20px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10px; 
            color: #333; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 16px; 
        }
        .header p { 
            margin: 4px 0; 
            font-size: 12px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: auto;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 6px; 
            text-align: left; 
            /* Evita que las URLs largas rompan la tabla */
            word-wrap: break-word; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
            font-size: 11px;
        }
        .footer { 
            position: fixed; 
            bottom: 0px; 
            left: 0px; 
            right: 0px; 
            height: 30px; 
            text-align: center; 
            font-size: 9px; 
            color: #777;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar-placeholder {
            width: 40px; 
            height: 40px; 
            border-radius: 50%; 
            background-color: #eee; 
            text-align: center; 
            line-height: 40px; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>{{ $course->subject->name }} - {{ $course->name }} ({{ $course->year }})</h1>
        <p>Lista de Alumnos</p>
        <p><small>Generado el: {{ $generationDate }}</small></p>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    @if($selectedColumns->contains('photo')) <th style="width: 50px;">Foto</th> @endif
                    @if($selectedColumns->contains('name')) <th>Nombre</th> @endif
                    @if($selectedColumns->contains('email')) <th>Email</th> @endif
                    @if($selectedColumns->contains('dni')) <th style="width: 70px;">DNI</th> @endif
                    @if($selectedColumns->contains('phone')) <th style="width: 80px;">Teléfono</th> @endif
                    @if($selectedColumns->contains('date_of_birth')) <th style="width: 75px;">Fecha de Nac.</th> @endif
                    @if($selectedColumns->contains('linkedin_url')) <th>LinkedIn</th> @endif
                    @if($selectedColumns->contains('github_url')) <th>GitHub</th> @endif
                    @if($selectedColumns->contains('professional_url')) <th>Portafolio</th> @endif
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        @if($selectedColumns->contains('photo')) 
                            <td>
                                @if($student->photo_base64)
                                    <img src="{{ $student->photo_base64 }}" class="avatar" alt="Foto">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                    </div>
                                @endif
                            </td> 
                        @endif
                        @if($selectedColumns->contains('name')) <td>{{ $student->name }}</td> @endif
                        @if($selectedColumns->contains('email')) <td>{{ $student->email }}</td> @endif
                        @if($selectedColumns->contains('dni')) <td>{{ $student->dni }}</td> @endif
                        @if($selectedColumns->contains('phone')) <td>{{ $student->phone }}</td> @endif
                        @if($selectedColumns->contains('date_of_birth')) 
                            <td>
                                {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : '' }}
                            </td> 
                        @endif
                        @if($selectedColumns->contains('linkedin_url')) <td>{{ $student->linkedin_url }}</td> @endif
                        @if($selectedColumns->contains('github_url')) <td>{{ $student->github_url }}</td> @endif
                        @if($selectedColumns->contains('professional_url')) <td>{{ $student->professional_url }}</td> @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $selectedColumns->count() }}" style="text-align: center;">No hay alumnos en la lista.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>

    <footer class="footer">
        SiGeAc &copy; {{ date('Y') }} - Página <span class="pagenum"></span>
    </footer>
</body>
</html>