-- Backup dr_goiz_pairs Table

CREATE TABLE dr_goiz_pairs_backup_20260521 AS
SELECT * FROM dr_goiz_pairs;

-- Update dr_goiz_pairs Table

UPDATE dr_goiz_pairs
SET
    name = REPLACE(name, 'PROTOCOL', 'REFERENCE'),
    name = REPLACE(name, 'Protocol', 'Reference'),
    name = REPLACE(name, 'protocol', 'reference'),

    description = REPLACE(description, 'PROTOCOL', 'REFERENCE'),
    description = REPLACE(description, 'Protocol', 'Reference'),
    description = REPLACE(description, 'protocol', 'reference'),

    characteristic = REPLACE(characteristic, 'PROTOCOL', 'REFERENCE'),
    characteristic = REPLACE(characteristic, 'Protocol', 'Reference'),
    characteristic = REPLACE(characteristic, 'protocol', 'reference'),

    name_es = REPLACE(name_es, 'Protocolo de', 'Referencia de'),
    name_es = REPLACE(name_es, 'Protocolo De', 'Referencia De'),
    name_es = REPLACE(name_es, 'protocolo de', 'referencia de'),

    description_es = REPLACE(description_es, 'Protocolo de', 'Referencia de'),
    description_es = REPLACE(description_es, 'Protocolo De', 'Referencia De'),
    description_es = REPLACE(description_es, 'protocolo de', 'referencia de'),

    characteristic_es = REPLACE(characteristic_es, 'Protocolo de', 'Referencia de'),
    characteristic_es = REPLACE(characteristic_es, 'Protocolo De', 'Referencia De'),
    characteristic_es = REPLACE(characteristic_es, 'protocolo de', 'referencia de');


-- Backup pairs Table

CREATE TABLE pairs_backup_20260521 AS
SELECT * FROM pairs;

/* =========================================================
   1. SYMPTOMS COLUMN REPLACEMENTS
   ========================================================= */

/* DIARRHEA / DIARREA / INTESTINAL */
UPDATE pairs
SET symptoms =
REPLACE(
    REPLACE(
        REPLACE(
            symptoms,
            'DIARRHEA / DIARREA / INTESTINAL',
            'DIGESTIVE GRID LAYOUT SHIFTS / CAMBIOS EN EL DISEÑO DE LA RED DIGESTIVA'
        ),
        'DIARRHEA',
        'DIGESTIVE GRID LAYOUT SHIFTS'
    ),
    'DIARREA',
    'CAMBIOS EN EL DISEÑO DE LA RED DIGESTIVA'
)
WHERE symptoms LIKE '%DIARRHEA%'
   OR symptoms LIKE '%DIARREA%'
   OR symptoms LIKE '%INTESTINAL%';


/* FEVER / FIEBRE / INFECTIONS */
UPDATE pairs
SET symptoms =
REPLACE(
    REPLACE(
        REPLACE(
            REPLACE(
                symptoms,
                'FEVER / FIEBRE / INFECTIONS',
                'CORE TEMPERATURE SPIKES AND SYSTEMIC FRICTION / PICOS DE TEMPERATURA CENTRAL Y FRICCIÓN SISTÉMICA'
            ),
            'FEVER',
            'CORE TEMPERATURE SPIKES AND SYSTEMIC FRICTION'
        ),
        'FIEBRE',
        'PICOS DE TEMPERATURA CENTRAL Y FRICCIÓN SISTÉMICA'
    ),
    'INFECTIONS',
    'SYSTEMIC FRICTION'
)
WHERE symptoms LIKE '%FEVER%'
   OR symptoms LIKE '%FIEBRE%'
   OR symptoms LIKE '%INFECTIONS%'
   OR symptoms LIKE '%INFECCIONES%';


/* PAIN / DOLOR / TENSION */
UPDATE pairs
SET symptoms =
REPLACE(
    REPLACE(
        REPLACE(
            REPLACE(
                symptoms,
                'PAIN / DOLOR / TENSION',
                'LOCALIZED BIOFIELD TENSION AND RESISTANCE / TENSIÓN Y RESISTENCIA DEL BIOCAMPO LOCALIZADA'
            ),
            'PAIN',
            'LOCALIZED BIOFIELD TENSION AND RESISTANCE'
        ),
        'DOLOR',
        'TENSIÓN Y RESISTENCIA DEL BIOCAMPO LOCALIZADA'
    ),
    'TENSION',
    'BIOFIELD RESISTANCE'
)
WHERE symptoms LIKE '%PAIN%'
   OR symptoms LIKE '%DOLOR%'
   OR symptoms LIKE '%TENSION%'
   OR symptoms LIKE '%TENSIÓN%';


/* ALLERGIES / TOXIC / TUMOR */
UPDATE pairs
SET symptoms =
REPLACE(
    REPLACE(
        REPLACE(
            symptoms,
            'ALLERGIES / TOXIC / TUMOR',
            'BIOFIELD ANOMALIES AND STRUCTURAL DATA CONGESTION / ANOMALÍAS DEL BIOCAMPO Y CONGESTIÓN DE DATOS ESTRUCTURALES'
        ),
        'ALLERGIES',
        'BIOFIELD ANOMALIES'
    ),
    'TUMOR',
    'STRUCTURAL DATA CONGESTION'
)
WHERE symptoms LIKE '%ALLERGIES%'
   OR symptoms LIKE '%TOXIC%'
   OR symptoms LIKE '%TUMOR%';


/* CHRONIC CONDITIONS / HEREDITARY / MIASMATIC */
UPDATE pairs
SET symptoms =
'OPTIMIZES LONG-TERM BIOFIELD STRUCTURAL EQUILIBRIUM AND BALANCES ANCESTRAL DATA LOGS. / OPTIMIZA EL EQUILIBRIO ESTRUCTURAL DEL BIOCAMPO A LARGO PLAZO Y EQUILIBRA LOS REGISTROS DE DATOS ANCESTRALES.'
WHERE symptoms LIKE '%chronic%'
   OR symptoms LIKE '%hereditary%'
   OR symptoms LIKE '%miasmatic%'
   OR symptoms LIKE '%crónicas%'
   OR symptoms LIKE '%hereditari%'
   OR symptoms LIKE '%miasm%';


/* FEAR / TRAUMA / STRESS */
UPDATE pairs
SET symptoms =
'ADDRESSES DEEP CORE SYSTEM RESTRICTIONS, EMOTIONAL DENSITY ACCUMULATION, AND ENVIRONMENTAL STRESS RETENTION WITHIN THE PRIMARY BIOFIELD. / ABORDA RESTRICCIONES PROFUNDAS DEL SISTEMA CENTRAL, ACUMULACIÓN DE DENSIDAD EMOCIONAL Y RETENCIÓN DE ESTRÉS AMBIENTAL DENTRO DEL BIOCAMPO PRIMARIO.'
WHERE symptoms LIKE '%fear and trauma%'
   OR symptoms LIKE '%deep-seated anxiety%'
   OR symptoms LIKE '%retention of stress%'
   OR symptoms LIKE '%miedo%'
   OR symptoms LIKE '%trauma%'
   OR symptoms LIKE '%estrés%';


/* PRIDE / EGO / RIGIDITY */
UPDATE pairs
SET symptoms =
'MAINTAINS MATRIX FLEXIBILITY, BALANCING STRUCTURAL DATA RIGIDITY AND SYSTEMIC ALIGNMENT PARAMETERS. / MANTIENE LA FLEXIBILIDAD DE LA MATRIZ, EQUILIBRANDO LA RIGIDEZ DE LOS DATOS ESTRUCTURALES Y LOS PARÁMETROS DE ALINEACIÓN SISTÉMICA.'
WHERE symptoms LIKE '%pride%'
   OR symptoms LIKE '%emotional rigidity%'
   OR symptoms LIKE '%stiff ego%'
   OR symptoms LIKE '%ego%'
   OR symptoms LIKE '%rigidez%';


/* ADRENAL / CORTISOL / FATIGUE */
UPDATE pairs
SET symptoms =
'SYSTEM WORKLOAD REGULATION. OPTIMIZES LONG-TERM ADAPTOGENIC EQUILIBRIUM, BALANCING VITAL ENERGY RESERVES AND SYSTEM FATIGUE LOGS. / REGULACIÓN DE LA CARGA DE TRABAJO DEL SISTEMA. OPTIMIZA EL EQUILIBRIO ADAPTÓGENO A LARGO PLAZO, EQUILIBRANDO LAS RESERVAS DE ENERGÍA VITAL Y LOS REGISTROS DE FATIGA DEL SISTEMA.'
WHERE symptoms LIKE '%adrenal dysfunction%'
   OR symptoms LIKE '%cortisol imbalance%'
   OR symptoms LIKE '%chronic fatigue%';


/* ASTHMA / RESPIRATORY */
UPDATE pairs
SET symptoms =
'AIRFLOW VECTOR FRICTION, LOCALIZED STRUCTURAL DENSITY VARIATIONS, AND UPPER NETWORK COMMUNICATION DELAYS. / FRICCIÓN DEL VECTOR DE FLUJO DE AIRE, VARIACIONES DE DENSIDAD ESTRUCTURAL LOCALIZADA Y RETRASOS EN LA COMUNICACIÓN DE LA RED SUPERIOR.'
WHERE symptoms LIKE '%asthma%'
   OR symptoms LIKE '%respiratory distress%'
   OR symptoms LIKE '%lung congestion%'
   OR symptoms LIKE '%pleuritis%';


/* DERMATITIS / CYSTITIS / ETC */
UPDATE pairs
SET symptoms =
'SYSTEMIC FILTRATION GRID CONGESTION, LOCALIZED FLUID VECTOR FRICTION, SURFACE TEXTURE VARIATIONS, AND PELVIC BIOFIELD TENSION. / CONGESTIÓN DE LA RED DE FILTRACIÓN SISTÉMICA, FRICCIÓN DE VECTORES DE FLUIDOS LOCALIZADOS, VARIACIONES EN LA TEXTURA DE LA SUPERFICIE Y TENSIÓN DEL BIOCAMPO PÉLVICO.'
WHERE symptoms LIKE '%Dermatitis%'
   OR symptoms LIKE '%cystitis%'
   OR symptoms LIKE '%proteinuria%'
   OR symptoms LIKE '%hematuria%'
   OR symptoms LIKE '%menorrhagia%'
   OR symptoms LIKE '%coliuria%'
   OR symptoms LIKE '%dysuria%'
   OR symptoms LIKE '%pleura pain%';


/* =========================================================
   2. PATHS COLUMN REPLACEMENTS
   ========================================================= */

/* ACIDIC / ALKALINE / PH */
UPDATE pairs
SET paths =
'CORRECTING POLARITY IMBALANCES IN THE TARGETED DATA ZONES / CORRIGIENDO DESEQUILIBRIOS DE POLARIDAD EN LAS ZONAS DE DATOS OBJETIVO'
WHERE paths LIKE '%NEUTRALIZING THE ACIDIC/ALKALINE IMBALANCE%'
   OR paths LIKE '%acidic%'
   OR paths LIKE '%alkaline%'
   OR paths LIKE '%pH%'
   OR paths LIKE '%ácido%'
   OR paths LIKE '%alcalino%';


/* VIRAL / BACTERIAL */
UPDATE pairs
SET paths =
'REALIGNING THE BIOFIELD GRID AND STABILIZING CORE DATABASE VALUES / REALINEANDO LA RED DEL BIOCAMPO Y ESTABILIZANDO LOS VALORES CENTRALES DE LA BASE DE DATOS'
WHERE paths LIKE '%ELIMINATE VIRAL RESONANCE%'
   OR paths LIKE '%CLEAR THE BACTERIAL LOAD%'
   OR paths LIKE '%viral%'
   OR paths LIKE '%bacterial%';


/* OSTEO / ARTHRITIS */
UPDATE pairs
SET paths =
'STRUCTURAL INTERFACE FRICTION, JOINT DENSITY LOAD VARIATIONS, AND SKELETAL ALIGNMENT DATA RESISTANCE. / FRICCIÓN DE LA INTERFAZ ESTRUCTURAL, VARIACIONES DE CARGA EN LA DENSIDAD ARTICULAR Y RESISTENCIA DE DATOS DE ALINEACIÓN ESQUELÉTICA.'
WHERE paths LIKE '%OSTEOARTHRITIS%'
   OR paths LIKE '%ARTHRITIS%'
   OR paths LIKE '%SPONDYLITIS%'
   OR paths LIKE '%BONE DISEASES%';


/* NEPHRITIS / CYSTITIS / PROSTATITIS */
UPDATE pairs
SET paths =
'LOWER FILTRATION GRID CONGESTION, LOCALIZED FLUID VECTOR FRICTION, AND PELVIC BIOFIELD TENSION. / CONGESTIÓN DE LA RED DE FILTRACIÓN INFERIOR, FRICCIÓN DE VECTORES DE FLUIDOS LOCALIZADOS Y TENSIÓN DEL BIOCAMPO PÉLVICO.'
WHERE paths LIKE '%NEPHRITIS%'
   OR paths LIKE '%CYSTITIS%'
   OR paths LIKE '%PROSTATITIS%'
   OR paths LIKE '%PELVIC PAIN%';


/* BLADDER / YIN / FEAR */
UPDATE pairs
SET paths =
'THE PRIMARY FLUID INFRASTRUCTURE SERVES AS A CORE RESONANCE ANCHOR. CALIBRATING THIS POLARITY CLEARS HISTORICAL SYSTEM BLOCKS AND RESTORES ADAPTIVE PROCESSING DATA FLOW. / LA INFRAESTRUCTURA DE FLUIDOS PRIMARIA SIRVE COMO UN ANCLA DE RESONANCIA CENTRAL. CALIBRAR ESTA POLARIDAD LIMPIA LOS BLOQUEOS HISTÓRICOS DEL SISTEMA Y RESTAURA EL FLUJO DE DATOS DE PROCESAMIENTO ADAPTATIVO.'
WHERE paths LIKE '%bladder organ energy system%'
   OR paths LIKE '%yang to kidney yin%'
   OR paths LIKE '%freeze in fear%';


/* ADRENAL / LIVER / EGO */
UPDATE pairs
SET paths =
'THIS CIRCUIT GOVERNS PROCESSING THROUGHPUT. BALANCING THESE SPECIFIC DATA NODES NEUTRALIZES PROCESSING GRID OVERLOAD AND STABILIZES BIOLOGICAL COMMUNICATION CHANNELS. / ESTE CIRCUITO GOBIERNA EL RENDIMIENTO DEL PROCESAMIENTO. EL EQUILIBRIO DE ESTOS NODOS DE DATOS ESPECÍFICOS NEUTRALIZA LA SOBRECARGA DE LA RED DE PROCESAMIENTO Y ESTABILIZA LOS CANALES DE COMUNICACIÓN BIOLÓGICA.'
WHERE paths LIKE '%adrenal/liver axis%'
   OR paths LIKE '%chemical overload in adrenals%'
   OR paths LIKE '%stiffness of the ego%';