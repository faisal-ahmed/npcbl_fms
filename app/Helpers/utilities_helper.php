<?php
/**
 * Custom utility helper
 * Migrated from CodeIgniter 3
 */

// Define constants
if (!defined('SUPER_ADMIN_ROLE_ID')) define('SUPER_ADMIN_ROLE_ID', '1');
if (!defined('CASUAL_LEAVE_ID')) define('CASUAL_LEAVE_ID', '1');
if (!defined('TANZIAR_PID')) define('TANZIAR_UID', '1690');
if (!defined('FAISAL_PID')) define('FAISAL_UID', '29');
if (!defined('DEFAULT_PASSWORD')) define('DEFAULT_PASSWORD', 'rnpp#246');
if (!defined('MD_PAYROLL_ID')) define('MD_PAYROLL_ID', '1001065');
if (!defined('NO_SUPERVISOR_UPTO_GRADE')) define('NO_SUPERVISOR_UPTO_GRADE', '5');
if (!defined('SMS_TOKEN')) define('SMS_TOKEN', '48941310091715152209b01eebc6b087a24d26688499ecf93b3b');


if (!function_exists('send_npcbl_sms')) {
    /**
     * @param array|string $to Single number or array of numbers
     * @param string $message The SMS body
     * @return array Response from the API
     */
    function send_npcbl_sms(array|string $to, string $message): array
    {
        $url = "http://api.greenweb.com.bd/api.php?json";
        $results = [];

        // Ensure $to is an array for consistent processing
        $numbers = is_array($to) ? $to : [$to];

        foreach ($numbers as $number) {
            // Clean number: Remove spaces or dashes if any
            $clean_number = preg_replace('/[^0-9]/', '', $number);

            $data = [
                'to' => $clean_number,
                'message' => $message,
                'token' => SMS_TOKEN
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Don't hang the app if API is slow

            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            $results[] = [
                'number' => $clean_number,
                'response' => json_decode($response, true),
                'error' => $error
            ];
        }

        return $results;
    }
}

if (!function_exists('get_sms_template')) {
    /**
     * @param string $applicantName Name of the person applying
     * @param string $leaveType Type of leave (CL, EL, etc.)
     * @param int $status 0=Applied, 1=Approved, 2=Rejected/Modified (Optional)
     * @return string The formatted SMS message
     */
    function get_sms_template(string $applicantName, string $leaveType, int $status = 0): string
    {
        $nameUpper = strtoupper($applicantName);

        return match ($status) {
            0 => "NPCBL LEAVE: $nameUpper has submitted a $leaveType application. Please review.",
            1 => "NPCBL LEAVE: Dear $nameUpper, your $leaveType application has been APPROVED.",
            2 => "NPCBL LEAVE: Dear $nameUpper, your $leaveType application has been REJECTED.",
            3 => "NPCBL LEAVE: Dear $nameUpper, your $leaveType has been RECOMMENDED by Supervisor & is pending Final Approval.",
            default => "NPCBL HRM: Update on $leaveType application for $nameUpper.",
        };
    }
}

if (!function_exists('notify_leave_status')) {
    /**
     * Helper to fetch user data and send a status-based SMS
     */
    function notify_leave_status($userId, string $applicantName, string $leaveType, int $status, $userModel): void
    {
        $user = $userModel->getUserData($userId);
        $phone = $user['Contact_Number'] ?? null;

        if ($phone) {
            $message = get_sms_template($applicantName, $leaveType, $status);
            send_npcbl_sms($phone, $message);
        }
    }
}

if (!function_exists('get_npp_security_fields')) {
    function get_npp_security_fields(): array
    {
        $jsFields = [
            'client_time', 'user_timezone', 'device_info', 'platform',
            'screen_resolution', 'language', 'cookies_enabled', 'color_depth',
            'hardware_concurrency', 'touch_support', 'referrer', 'device_memory',
            'connection_type', 'is_secure_context', 'permissions_status',
            'client_ip', 'ip_country', 'ip_region', 'ip_city', 'ip_organization'
        ];

        sort($jsFields);
        return $jsFields;
    }
}

if (!function_exists('get_colors')) {
    function get_colors(): array
    {
        $colors = [
            'color1', 'color2', 'color3', 'color4', 'color5', 'color6', 'color7', 'color8', 'color9',
        ];

        sort($colors);
        return $colors;
    }
}

if (!function_exists('get_all_districts')) {
    function get_all_districts(): array
    {
        $districts = [
            'Bagerhat', 'Bandarban', 'Barguna', 'Barisal', 'Bhola', 'Bogura', 'Brahmanbaria',
            'Chandpur', 'Chapai Nawabganj', 'Chattogram', 'Chuadanga', 'Comilla', 'Cox\'s Bazar',
            'Dhaka', 'Dinajpur', 'Faridpur', 'Feni', 'Gaibandha', 'Gazipur', 'Gopalganj',
            'Habiganj', 'Jamalpur', 'Jashore', 'Jhalokathi', 'Jhenaidah', 'Joypurhat',
            'Khagrachhari', 'Khulna', 'Kishoreganj', 'Kurigram', 'Kushtia', 'Lakshmipur',
            'Lalmonirhat', 'Madaripur', 'Magura', 'Manikganj', 'Meherpur', 'Moulvibazar',
            'Munshiganj', 'Mymensingh', 'Naogaon', 'Narail', 'Narayanganj', 'Narsingdi',
            'Natore', 'Netrokona', 'Nilphamari', 'Noakhali', 'Pabna', 'Panchagarh', 'Patuakhali',
            'Pirojpur', 'Rajbari', 'Rajshahi', 'Rangamati', 'Rangpur', 'Satkhira', 'Shariatpur',
            'Sherpur', 'Sirajganj', 'Sunamganj', 'Sylhet', 'Tangail', 'Thakurgaon'
        ];

        sort($districts);
        return $districts;
    }
}

if (!function_exists('get_npp_departments')) {
    function get_npp_departments(): array
    {
        $departments = [
            "Accounts",
            "Accounts & HR",
            "Administration",
            "Admin & HR",
            "Applied Physics",
            "C & RW",
            "Chemical",
            "Chemistry",
            "Civil",
            "Company Secretariat",
            "Computer",
            "Cooling & AC",
            "E&E",
            "Electrical",
            "Electronics",
            "Environment",
            "Environment and Social",
            "Finance",
            "Finance & Accounts",
            "Finance & Administration",
            "Fire Safety",
            "HR",
            "HR & Administration",
            "IT & Communication",
            "IT & Telecommunications",
            "Inspection & Violation Investigation",
            "Instrumentation",
            "Junior Crane Operator",
            "Library & Documentation",
            "Licencing & Regulatory Compliance",
            "Marketing",
            "Mechanical",
            "Medical",
            "Modernization & Equipment Life Cycle Management",
            "Nuclear",
            "Nuclear Engineering",
            "Office Staff",
            "Others",
            "Physics",
            "Physics-Chemistry",
            "PPS",
            "Public Relation Officer",
            "Pump",
            "S & PPSD",
            "Safety & Quality",
            "Software",
            "Technical Diagnostics",
            "Training Division",
            "Welding",
        ];

        sort($departments);
        return $departments;
    }
}

if (!function_exists('get_npp_divisions')) {
    function get_npp_divisions(): array
    {
        $divisions = [
            "Department of Mobilization Training & Civil Defence (DoMTCD)",
            "E&E",
            "Administration",
            "Technical Services Division (Headquarter)",
            "HR & Administration",
            "Licensing and Regulatory Compliance",
            "HR & Accounts",
            "Finance & Administration",
            "Library and Documentation",
            "Others",
            "APCS-EE",
            "Capital Construction",
            "Operations",
            "Chemical Technologies & RAW Handling",
            "Safety & Reliability",
            "Maintenance & Repair",
            "Technical Service & Modernization",
            "Inspection",
            "Training Division",
            "Management",
            "Finance & Administration",
            "PPSD"
        ];

        sort($divisions);
        return $divisions;
    }
}

if (!function_exists('get_npp_shops')) {
    function get_npp_shops(): array
    {
        $shops = [
            "Nuclear Safety & Reliability",
            "FSS Laboratory",
            "Operational Personnel Training Department",
            "General Training Department",
            "Training Organization Department",
            "Technical Training Means Operational Department",
            "Methodological Support (Instruction) Department",
            "Administrative Service Group",
            "Licensing and Regulatory Compliance",
            "Operating Experience & Investigation of Violations Department (OEIVD)",
            "NDT Department",
            "Metal Non Destructive Testing & Technical Inspection Department (MNDT&TID)",
            "Inspection & Safety Assurance Department",
            "Occupational Health & Labour Safety",
            "Head of Inspection Department",
            "Technical Inspection and Industrial Safety Department (TIISD)",
            "Common Plant Repair Shop (CPRS)",
            "Department of Mobilization Training & Civil Defence (DoMTCD)",
            "Pre-Production Sub-Division",
            "Environment Protection Department",
            "Centralized Repair Shop (Preproduction Subdivision)",
            "Others",
            "Process Management Department (PMD)",
            "Radiation Safety",
            "Maintenance & Repair",
            "Maintenance & Repair (Personnel Training Department)",
            "Instrumentation Shop",
            "Electrical Shop",
            "IT and Communication",
            "Metrology Department",
            "Capital Construction Management Department",
            "Reactor Shop",
            "Venitlation Shop",
            "Support Systems Shop",
            "Turbine Shop",
            "RAW Treatment Shop",
            "Chemical Shop",
            "Decontamination Shop",
            "Centralized Repair Shop (Turbine)",
            "RPPD",
            "Centralized Repair Shop (Reactor)",
            "CPS Drive SheEM-3 Repair",
            "Fire Safety Department",
            "Quality Management Department",
            "Technical Diagnostic",
            "Resource Modernization & Extension of Service Life",
            "Production & Technical",
            "Operation Engineering Support"
        ];

        sort($shops);
        return $shops;
    }
}