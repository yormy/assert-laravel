<?php

return [

    // leave the extensions empty for all allowed extensions
    'upload' => [
        'mime_allowed' => [
            'image/jpeg' => [
                'jpg',
                'jpeg',
            ],
            'image/gif' => [],
            'image/png' => [],
            'image/bmp' => [],
            'application/pdf' => [],
            'application/octet-stream' => [
                'mp4',
            ],
            'video/mp4' => [
                'mp4',
            ],
            'application/vnd.oasis.opendocument.text' => [
                'odt',
            ],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => [
                'docx',
            ],
            'application/msword' => [
                'doc',
            ],
        ],

        'max_file_size_kb' => 600000000,

        'inconsistent_extension' => true,

        'inconsistent_mime' => true,

        'inconsistent_mime_extension' => true,

        'content_blacklist' => [
            '<?php',
            'bzip2://',
            'expect://',
            'glob://',
            'phar://',
            'php://',
            'ogg://',
            'rar://',
            'ssh2://',
            'zip://',
            'zlib://',
        ],

        'exceptions' => [
            'NullByteFoundException',
            'SizeNotAllowedException',
            'MimeTypeNotAllowedException',
            'InconsistentMimeException',
            'ExtensionInconsistentException',
            'ExtensionInvalidException',
            'BlacklistedContentException',
        ],

    ],

];
