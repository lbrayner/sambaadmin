<?php
    interface i_password {
    }

    class passwd {
        static function meta_find_user_for_mail($metafp, $email) {
            while ( ! feof ( $metafp ) && $meta = explode ( ":",
                $line = rtrim ( fgets ( $metafp ) ) ) ) {
                if (count ( $meta ) > 1) {
                    $username = trim ( $meta [0] );
                    $lemail = $meta [1];

                    if ($lemail == $email) {
                        return $username;
                    }
                }
            }
            return null;
        }

        static function get_metadata($metafp) {
            rewind ( $metafp );
            $meta_model_map = array ();
            $metaarr = array ();
            while ( ! feof ( $metafp ) && $line = rtrim ( fgets ( $metafp ) ) ) {
                $metaarr = explode ( ":", $line );
                $model = new meta_model ();
                $model->user = $metaarr [0];
                if (count ( $metaarr ) > 1) {
                    $model->email = $metaarr [1];
                }
                if (count ( $metaarr ) > 2) {
                    $model->name = $metaarr [2];
                }
                if (count ( $metaarr ) > 3) {
                    $model->mailkey = $metaarr [3];
                }

                $meta_model_map [$model->user] = $model;
            }
            return $meta_model_map;
        }

        static function meta_add($metafp, meta_model $meta_model) {
            if (self::exists ( $metafp, $meta_model->user )) {
                return false;
            }
            fseek ( $metafp, 0, SEEK_END );
            fwrite ( $metafp, $meta_model->user . ':' .
                $meta_model->email . ':' . $meta_model->name . ':' .
                $meta_model->mailkey . "\n" );
            return true;
        }

        static function exists($fp, $username) {
            rewind ( $fp );
            while ( ! feof ( $fp ) ) {
                $strings = explode ( ":", $line = rtrim ( fgets ( $fp ) ) );
                $lusername = array_shift ($strings);
                if (!trim($lusername))
                    break;
                if ($lusername == $username)
                    return true;
            }
            return false;
        }

        static function delete($fp, $username, $filename, $dorewind = true) {
            $data = '';
            $pos = ftell($fp);
            if ($dorewind) {
                rewind ( $fp );
            }
            while ( ! feof ( $fp ) ) {
                $strings = explode ( ":", $line = rtrim ( fgets ( $fp ) ) );
                $lusername = array_shift ($strings);
                if (!trim($lusername))
                    break;
                if ($lusername != $username)
                    $data .= $line . "\n";
            }
            $fp = fopen ( $filename, 'r+' );
            if (!$dorewind) {
                fseek($fp, $pos);
            }
            fwrite ( $fp, rtrim ( $data ) . (trim ( $data ) ? "\n" : '') );
            ftruncate( $fp, ftell($fp));
            fclose ( $fp );
            $fp = fopen ( $filename, 'r+' );
            return true;
        }

        static function open_or_create($filename) {
            if (! file_exists ( $filename )) {
                return fopen ( $filename, 'w+' );
            } else {
                return fopen ( $filename, 'r+' );
            }
        }

        static function htcrypt($password) {
            return password_hash($password,PASSWORD_DEFAULT);
        }

        static function check_password_hash($password, $hash) {
            return password_verify($password, $hash);
        }

    }
?>
