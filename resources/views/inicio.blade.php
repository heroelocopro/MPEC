<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Título SEO -->
    <title>Guru Educativa | Plataforma Escolar Colombia - Corporalma</title>

    <!-- Descripción SEO -->
    <meta
      name="description"
      content="Guru Educativa es una plataforma educativa desarrollada en Colombia para colegios y escuelas modernas. Conecta a estudiantes, docentes, directivos y acudientes en una experiencia integral de gestión académica. Desarrollada por la Corporación de Altos Estudios del Magdalena - Corporalma."
    />

    <!-- Palabras clave -->
    <meta
      name="keywords"
      content="plataforma educativa, colegios Colombia, gestión escolar, software educativo, GurupEducativa, Corporalma, Corporación de Altos Estudios del Magdalena, Girardot, docentes, estudiantes, exámenes virtuales, horarios escolares, notas académicas, foros educativos, educación Colombia"
    />

    <!-- Autor y entidad -->
    <meta
      name="author"
      content="Corporación de Altos Estudios del Magdalena - Corporalma"
    />

    <!-- Favicon -->
    <link rel="shortcut icon" href="./icon.png" type="image/png" />

    <!-- Canonical URL -->
    <link rel="canonical" href="https://mpec-production.up.railway.app/" />

    <!-- Open Graph / Facebook -->
    <meta
      property="og:title"
      content="GurupEducativa | Plataforma Escolar Integral"
    />
    <meta
      property="og:description"
      content="Plataforma educativa colombiana para colegios y escuelas. Gestiona notas, horarios, exámenes y más. Desarrollada por Corporalma."
    />
    <meta
      property="og:image"
      content="https://mpec-production.up.railway.app/GurupTodosModulosneeddfix.png"
    />
    <meta property="og:url" content="https://mpec-production.up.railway.app/" />
    <meta property="og:type" content="website" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta
      name="twitter:title"
      content="Gurup ducativa | Plataforma Escolar Integral en Colombia"
    />
    <meta
      name="twitter:description"
      content="Gestión académica moderna para colegios, docentes y estudiantes. Colombia - Corporalma."
    />
    <meta
      name="twitter:image"
      content="https://mpec-production.up.railway.app/GurupTodosModulosneeddfix.png"
    />

    <!-- Color del navegador -->
    <meta name="theme-color" content="#4f46e5" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body
    class="bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-100 font-sans"
  >
    <!-- Hero principal -->
    <header
      class="bg-gradient-to-r from-indigo-600 to-blue-700 text-white py-20 px-6 text-center"
    >
      <h1 class="text-5xl md:text-6xl font-bold mb-4">Guru Educativa</h1>
      <p class="text-xl md:text-2xl mb-6">
        La plataforma educativa integral para instituciones modernas
      </p>
      <img
        class="w-1/4 rounded text-center mx-auto"
        src="{{ asset('images/GuruEducativa2.png') }}"
        alt=""
      />
      <br>
              <!-- Botón de acceso -->
        <a
          href="{{ route('login') }}"

          class="bg-white text-indigo-700 font-semibold px-6 py-3 rounded-full shadow hover:bg-gray-100 transition mb-5"
        >
          Ir a la Plataforma
        </a>
      <br>
      <br />
      <a
        href="#modulos"
        class="bg-white text-indigo-700 font-semibold px-6 py-3 rounded-full shadow hover:bg-gray-100 transition"
        >Descubrir módulos</a
      >
    </header>

    <!-- Sección sobre la plataforma -->
    <section class="max-w-5xl mx-auto py-16 px-6">
      <h2 class="text-3xl font-bold text-center mb-6">
        ¿Qué es Guru Educativa?
      </h2>
      <p
        class="text-center text-lg text-gray-600 dark:text-gray-300 leading-relaxed"
      >
        Guru Educativa es una solución web desarrollada para facilitar la
        gestión escolar en colegios y escuelas. Nuestra plataforma conecta a
        administradores, docentes, estudiantes y familias, brindando
        herramientas prácticas para el aprendizaje, evaluación, comunicación y
        organización institucional.
      </p>
    </section>

    <!-- Lightbox Overlay (oculto por defecto) -->
    <div
      id="lightbox"
      class="fixed inset-0 z-50 bg-black bg-opacity-80 flex items-center justify-center hidden"
    >
      <img
        id="lightbox-img"
        src=""
        alt="Vista ampliada"
        class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-2xl"
      />
    </div>

    <!-- Sección: Módulos del Administrador -->
    <section id="modulos" class="bg-gray-100 dark:bg-gray-800 py-16 px-6">
      <div class="max-w-6xl mx-auto">
        <h2
          class="text-3xl font-bold text-center mb-10 text-indigo-700 dark:text-indigo-300"
        >
          Módulos del Administrador
        </h2>
        <p class="text-center text-lg text-gray-600 dark:text-gray-300 mb-12">
          El administrador general tiene una visión completa de todos los
          colegios registrados. Puede consultar estadísticas, monitorear el uso
          del sistema y acceder a cada institución para verificar su desempeño.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Módulo: Panel general -->
          <div
            class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Administrador/PanelAdministrador.png') }}"
              alt="Panel Administrador"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Panel General
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Desde aquí se accede al resumen global del sistema: número de
                colegios registrados, estudiantes, docentes, exámenes activos,
                foros y más.
              </p>
            </div>
          </div>

          <!-- Módulo: Estadísticas por colegio -->
          <div
            class="bg-white dark:bg-gray-900 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Administrador/AdministradorEstadisticasColegio.png') }}"
              alt="Estadísticas por colegio"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Estadísticas por Colegio
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Visualiza el rendimiento por institución: asistencia, notas
                promedio, distribución por grados, materias más evaluadas y
                actividad docente.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Sección: Módulos del Colegio -->
    <section id="modulos-colegio" class="bg-white dark:bg-gray-900 py-16 px-6">
      <div class="max-w-6xl mx-auto">
        <h2
          class="text-3xl font-bold text-center mb-10 text-indigo-700 dark:text-indigo-300"
        >
          Módulos del Colegio
        </h2>
        <p class="text-center text-lg text-gray-600 dark:text-gray-300 mb-12">
          Los usuarios encargados del colegio pueden gestionar grupos,
          asignaturas, docentes, estudiantes y sus horarios, además de publicar
          anuncios y definir los periodos académicos.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Módulo: Panel Colegio -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/PanelColegio.png') }}"
              alt="Panel Colegio"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Panel General
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Vista general del estado académico de la institución: cantidad
                de grupos, asignaciones, docentes y más.
              </p>
            </div>
          </div>

          <!-- Módulo: Anuncios -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioAnuncios.png') }}"
              alt="Colegio Anuncios"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Anuncios Institucionales
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Publicación de comunicados, avisos y notificaciones generales a
                estudiantes, acudientes y docentes.
              </p>
            </div>
          </div>

          <!-- Módulo: Gestión de Grupos -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioGrupos.png') }}"
              alt="Gestión de Grupos"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Gestión de Grupos
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Crear, editar y organizar grupos por grado, incluyendo
                asignación de estudiantes y docentes.
              </p>
            </div>
          </div>

          <!-- Módulo: Horarios por Docente -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioGruposHorariosDocente.png') }}"
              alt="Horarios por Docente"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Horarios por Docente
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Configura y visualiza horarios académicos por grupo y docente,
                organizando materias y días.
              </p>
            </div>
          </div>

          <!-- Módulo: Asignar Materias por Grado -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioAsignamientoMateriaGrado.png') }}"
              alt="Asignar Materias por Grado"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Asignar Materias por Grado
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Define qué materias se dictan en cada grado académico para
                estructurar el currículo.
              </p>
            </div>
          </div>

          <!-- Módulo: Asignar Docente-Materia -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioAsignamientoDocenteMateria.png') }}"
              alt="Asignar Docente-Materia"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Asignar Docente a Materia
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Relaciona cada materia con su respectivo docente en el grupo
                correspondiente.
              </p>
            </div>
          </div>

          <!-- Módulo: Asignar Estudiantes a Grupos -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioAsignamientoEstudianteGrupo.png') }}"
              alt="Asignar Estudiantes a Grupos"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Asignar Estudiantes
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Organiza a los estudiantes por grupo y asegúrate de que estén
                correctamente distribuidos.
              </p>
            </div>
          </div>

          <!-- Módulo: Información por Grupo -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioGrupoInformacion.png') }}"
              alt="Información del Grupo"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Información del Grupo
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Consulta los datos generales de cada grupo, incluyendo
                asignaturas y docentes asignados.
              </p>
            </div>
          </div>

          <!-- Módulo: Estudiantes por Grupo -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioGrupoInformacionEstudiante.png') }}"
              alt="Estudiantes por Grupo"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Estudiantes por Grupo
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Revisa la lista completa de estudiantes por grupo y accede a sus
                perfiles individuales.
              </p>
            </div>
          </div>

          <!-- Módulo: Periodos Académicos -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Colegio/ColegioPeriodos.png') }}"
              alt="Periodos Académicos"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-indigo-600 dark:text-indigo-300"
              >
                Periodos Académicos
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Configura los cortes de evaluación por año lectivo y define
                fechas importantes.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Sección: Módulos del Docente -->
    <section
      id="modulos-docente"
      class="bg-gray-50 dark:bg-gray-950 py-16 px-6"
    >
      <div class="max-w-6xl mx-auto">
        <h2
          class="text-3xl font-bold text-center mb-10 text-purple-700 dark:text-purple-300"
        >
          Módulos del Docente
        </h2>
        <p class="text-center text-lg text-gray-600 dark:text-gray-300 mb-12">
          Los docentes pueden gestionar sus asignaturas, tomar asistencia,
          calificar exámenes y consultar su horario con facilidad.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Módulo: horario del Docente -->
          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Docente/DocenteNotasColores.png') }}"
              alt="Panel Docente"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-purple-700 dark:text-purple-300"
              >
                Horario Académico
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Visualiza de forma clara las asignaturas, horas y días de clase
                asignadas al docente.
              </p>
            </div>
          </div>

          <!-- Módulo: Exámenes -->
          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Docente/DocenteExamenesVer.png') }}"
              alt="Gestión de Exámenes"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-purple-700 dark:text-purple-300"
              >
                Gestión de Exámenes
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Crea, edita y consulta los exámenes asignados a sus grupos con
                facilidad.
              </p>
            </div>
          </div>

          <!-- Módulo: Registro de Notas -->
          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Docente/DocenteNotas.png') }}"
              alt="Registro de Notas"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-purple-700 dark:text-purple-300"
              >
                Registro de Notas
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Calificación de estudiantes por actividad, tarea, examen o
                periodo académico.
              </p>
            </div>
          </div>

          <!-- Módulo: Notas con Colores -->
          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Docente/DocenteNotasColores.png') }}"
              alt="Notas con Colores"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-purple-700 dark:text-purple-300"
              >
                Notas por Color
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Visualización rápida del rendimiento de los estudiantes con
                semáforo de colores.
              </p>
            </div>
          </div>

          <!-- Módulo: Asistencias -->
          <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Docente/DocenteAsistencias.png') }}"
              alt="Control de Asistencia"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-purple-700 dark:text-purple-300"
              >
                Control de Asistencia
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Registro de asistencia diaria por grupo y materia con soporte
                para justificaciones.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Sección: Módulos del Estudiante -->
    <section
      id="modulos-estudiante"
      class="bg-white dark:bg-gray-900 py-16 px-6"
    >
      <div class="max-w-6xl mx-auto">
        <h2
          class="text-3xl font-bold text-center mb-10 text-blue-700 dark:text-blue-300"
        >
          Módulos del Estudiante
        </h2>
        <p class="text-center text-lg text-gray-600 dark:text-gray-300 mb-12">
          Los estudiantes tienen acceso a sus actividades, exámenes, notas,
          horario y notificaciones dentro de un entorno claro y dinámico.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Panel del Estudiante -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Estudiante/PanelEstudiante.png') }}"
              alt="Panel Estudiante"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-blue-700 dark:text-blue-300"
              >
                Panel General
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Vista rápida de actividades pendientes, exámenes, notas
                recientes y accesos directos.
              </p>
            </div>
          </div>

          <!-- Actividades -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Estudiante/EstudianteActividades.png') }}"
              alt="Actividades Estudiante"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-blue-700 dark:text-blue-300"
              >
                Actividades
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Consulta y entrega de tareas, guías, trabajos y recursos
                asignados por los docentes.
              </p>
            </div>
          </div>

          <!-- Exámenes disponibles -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Estudiante/EstudianteExamen.png') }}"
              alt="Listado de Exámenes"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-blue-700 dark:text-blue-300"
              >
                Exámenes Disponibles
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Lista de evaluaciones activas con fecha de cierre y botón para
                iniciar.
              </p>
            </div>
          </div>

          <!-- Examen en curso -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Estudiante/EstudianteExamenHaciendolo.png') }}"
              alt="Examen en Curso"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-blue-700 dark:text-blue-300"
              >
                Examen en Progreso
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Interfaz amigable para responder preguntas con tiempo límite y
                guardado automático.
              </p>
            </div>
          </div>

          <!-- Notas Finales -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Estudiante/EstudianteNotasFinales.png') }}"
              alt="Notas Finales"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-blue-700 dark:text-blue-300"
              >
                Notas Finales
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Visualización detallada de notas por materia, periodo y color
                por nivel de rendimiento.
              </p>
            </div>
          </div>

          <!-- Horarios -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Estudiante/EstudianteHorario.png') }}"
              alt="Horario del Estudiante"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-blue-700 dark:text-blue-300"
              >
                Horario Académico
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Consulta clara del horario semanal con materias, docentes y
                aulas por día.
              </p>
            </div>
          </div>

          <!-- Notificaciones -->
          <div
            class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"
          >
            <img
              src="{{ asset('images/inicio/img/Estudiante/EstudianteNotificaciones.png') }}"
              alt="Notificaciones Estudiante"
              class="w-full h-56 object-cover cursor-zoom-in hover:opacity-90 transition"
              onclick="ampliarImagen(this)"
            />
            <div class="p-6">
              <h3
                class="text-xl font-semibold mb-2 text-blue-700 dark:text-blue-300"
              >
                Notificaciones
              </h3>
              <p class="text-gray-700 dark:text-gray-300">
                Alertas automáticas sobre nuevas tareas, mensajes del docente,
                cambios de horario o calificaciones.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Sección: Acceso y Credenciales de Prueba -->
    <section id="acceso" class="bg-blue-50 dark:bg-gray-800 py-16 px-6">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-blue-700 dark:text-blue-200 mb-6">
          Accede a la Plataforma
        </h2>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
          Explora GurupEducativa con estas credenciales de prueba para cada rol.
        </p>

        <!-- Botón de acceso -->
        <a
          href="https://mpec-production.up.railway.app/"
          target="_blank"
          class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-full text-lg shadow transition"
        >
          Ir a la Plataforma
        </a>

        <!-- Credenciales -->
        <div
          class="mt-12 text-left max-w-2xl mx-auto space-y-6 bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md"
        >
          <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-300">
            Credenciales de Prueba
          </h3>
          Proximamente
          <!-- <ul class="text-gray-800 dark:text-gray-200 space-y-2">
            <li>
              <strong>Administrador:</strong> admin@gmail.com / contraseña:
              admin
            </li>
            <li>
              <strong>Colegio:</strong> iemep546@plateform-educative.com /
              contraseña: 123456789
            </li>
            <li>
              <strong>Docente:</strong> docente@gmail.com / contraseña:
              123456789
            </li>
            <li>
              <strong>Estudiante:</strong> estudiante@gmail.com / contraseña:
              12345678
            </li>
          </ul> -->
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer
      class="bg-gray-100 dark:bg-gray-900 py-6 text-center text-gray-600 dark:text-gray-400 mt-10 border-t border-gray-300 dark:border-gray-700"
    >
      <p>
        &copy; 2025
        <strong><a href="https://corporalma.edu.co/">Corporalma</a> </strong>.
        Todos los derechos reservados.
      </p>
    </footer>

    <!-- JS para ampliar imagen -->
    <script>
      const lightbox = document.getElementById("lightbox");
      const lightboxImg = document.getElementById("lightbox-img");

      function ampliarImagen(imgElement) {
        lightbox.classList.remove("hidden");
        lightboxImg.src = imgElement.src;
        document.body.classList.add("overflow-hidden");
      }

      lightbox.addEventListener("click", () => {
        lightbox.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
      });
    </script>
  </body>
</html>
