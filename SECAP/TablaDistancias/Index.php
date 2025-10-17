<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['username'] !== 'admin') {
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Acceso denegado</title><style>body{font-family:Arial,sans-serif;background:#fee2e2;text-align:center;padding:4rem;}h1{color:#991b1b;}</style></head><body><h1>Acceso denegado</h1><p>Esta sección es solo para el administrador.</p><a href="../index.php">Volver al inicio</a></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Distancias - SECAP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #6b7280;
            font-size: 1rem;
        }

        .origin-info {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }

        .origin-info h2 {
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .origin-info p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .tables-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .table-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table-card h3 {
            background: #4f46e5;
            color: white;
            padding: 1rem;
            font-size: 1.3rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }

        tr:hover {
            background: #f9fafb;
        }

        .distance {
            font-weight: 600;
            color: #4f46e5;
        }

        .zone-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .zone-centro {
            background: #dcfce7;
            color: #166534;
        }

        .zone-caba {
            background: #dbeafe;
            color: #1e40af;
        }

        .zone-gba {
            background: #fef3c7;
            color: #92400e;
        }

        .zone-alejado {
            background: #fee2e2;
            color: #991b1b;
        }

        .search-box {
            margin-bottom: 1rem;
            padding: 0.75rem;
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        .search-box:focus {
            outline: none;
            border-color: #4f46e5;
        }

        @media (max-width: 768px) {
            .tables-container {
                grid-template-columns: 1fr;
            }
            
            body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📦 SECAP - Tabla de Distancias</h1>
            <p>Proyecto de Construcción de Software - UB</p>
        </div>

        <div class="origin-info">
            <h2>📍 Centro de Distribución SECAP</h2>
            <p><strong>Dirección:</strong> Villanueva 1324, Belgrano, CABA</p>
        </div>

        <input type="text" id="searchBox" class="search-box" placeholder="🔍 Buscar barrio o localidad...">

        <div class="tables-container">
            <!-- CABA Table -->
            <div class="table-card">
                <h3>CABA - Barrios</h3>
                <table id="cabaTable">
                    <thead>
                        <tr>
                            <th>Barrio</th>
                            <th>Distancia</th>
                            <th>Zona</th>
                        </tr>
                    </thead>
                    <tbody id="cabaBody">
                    </tbody>
                </table>
            </div>

            <!-- GBA Table -->
            <div class="table-card">
                <h3>Zona Norte - GBA</h3>
                <table id="gbaTable">
                    <thead>
                        <tr>
                            <th>Localidad</th>
                            <th>Distancia</th>
                            <th>Zona</th>
                        </tr>
                    </thead>
                    <tbody id="gbaBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Distancias desde Villanueva 1324, Belgrano
        const distances = {
            caba: [
                { name: 'Agronomía', km: 7 },
                { name: 'Almagro', km: 9 },
                { name: 'Balvanera', km: 10 },
                { name: 'Barracas', km: 14 },
                { name: 'Belgrano', km: 2 },
                { name: 'Boedo', km: 11 },
                { name: 'Caballito', km: 9 },
                { name: 'Chacarita', km: 5 },
                { name: 'Coghlan', km: 3 },
                { name: 'Colegiales', km: 3 },
                { name: 'Constitución', km: 12 },
                { name: 'Flores', km: 11 },
                { name: 'Floresta', km: 11 },
                { name: 'La Boca', km: 15 },
                { name: 'Liniers', km: 14 },
                { name: 'Mataderos', km: 16 },
                { name: 'Monte Castro', km: 13 },
                { name: 'Monserrat', km: 11 },
                { name: 'Nueva Pompeya', km: 13 },
                { name: 'Núñez', km: 2 },
                { name: 'Once', km: 10 },
                { name: 'Palermo', km: 4 },
                { name: 'Parque Avellaneda', km: 13 },
                { name: 'Parque Chacabuco', km: 11 },
                { name: 'Parque Chas', km: 5 },
                { name: 'Parque Patricios', km: 12 },
                { name: 'Paternal', km: 7 },
                { name: 'Puerto Madero', km: 11 },
                { name: 'Recoleta', km: 7 },
                { name: 'Retiro', km: 8 },
                { name: 'Saavedra', km: 5 },
                { name: 'San Cristóbal', km: 11 },
                { name: 'San Nicolás', km: 10 },
                { name: 'San Telmo', km: 12 },
                { name: 'Versalles', km: 13 },
                { name: 'Villa Crespo', km: 6 },
                { name: 'Villa del Parque', km: 8 },
                { name: 'Villa Devoto', km: 10 },
                { name: 'Villa Gral. Mitre', km: 8 },
                { name: 'Villa Lugano', km: 17 },
                { name: 'Villa Luro', km: 12 },
                { name: 'Villa Ortúzar', km: 4 },
                { name: 'Villa Pueyrredón', km: 6 },
                { name: 'Villa Real', km: 9 },
                { name: 'Villa Riachuelo', km: 18 },
                { name: 'Villa Santa Rita', km: 10 },
                { name: 'Villa Soldati', km: 14 },
                { name: 'Villa Urquiza', km: 5 }
            ],
            gba: [
                { name: 'Acassuso', km: 10 },
                { name: 'Béccar', km: 9 },
                { name: 'Caseros', km: 12 },
                { name: 'Castelar', km: 20 },
                { name: 'Ciudadela', km: 15 },
                { name: 'Don Torcuato', km: 24 },
                { name: 'El Palomar', km: 18 },
                { name: 'General Pacheco', km: 28 },
                { name: 'Haedo', km: 17 },
                { name: 'Hurlingham', km: 16 },
                { name: 'Ituzaingó', km: 19 },
                { name: 'La Lucila', km: 5 },
                { name: 'Martínez', km: 6 },
                { name: 'Morón', km: 17 },
                { name: 'Olivos', km: 4 },
                { name: 'Ramos Mejía', km: 14 },
                { name: 'San Fernando', km: 16 },
                { name: 'San Isidro', km: 8 },
                { name: 'San Martín', km: 14 },
                { name: 'Santos Lugares', km: 13 },
                { name: 'Tigre', km: 26 },
                { name: 'Tres de Febrero', km: 11 },
                { name: 'Vicente López', km: 3 },
                { name: 'Villa Ballester', km: 13 }
            ]
        };

        function getZone(km) {
            if (km <= 5) return { name: 'Muy Cerca', class: 'zone-centro' };
            if (km <= 12) return { name: 'CABA', class: 'zone-caba' };
            if (km <= 20) return { name: 'GBA Zona Norte', class: 'zone-gba' };
            return { name: 'GBA Alejado', class: 'zone-alejado' };
        }

        function renderTable(data, bodyId) {
            const tbody = document.getElementById(bodyId);
            tbody.innerHTML = '';
            
            data.sort((a, b) => a.name.localeCompare(b.name));
            
            data.forEach(item => {
                const zone = getZone(item.km);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td class="distance">${item.km} km</td>
                    <td><span class="zone-badge ${zone.class}">${zone.name}</span></td>
                `;
                tbody.appendChild(row);
            });
        }

        // Render initial tables
        renderTable(distances.caba, 'cabaBody');
        renderTable(distances.gba, 'gbaBody');

        // Search functionality
        document.getElementById('searchBox').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            const filteredCABA = distances.caba.filter(item => 
                item.name.toLowerCase().includes(searchTerm)
            );
            
            const filteredGBA = distances.gba.filter(item => 
                item.name.toLowerCase().includes(searchTerm)
            );
            
            renderTable(filteredCABA, 'cabaBody');
            renderTable(filteredGBA, 'gbaBody');
        });
    </script>
</body>
</html>