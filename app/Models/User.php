<?php

    namespace App\Models;

    use Laravel\Sanctum\HasApiTokens;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class User extends Authenticatable
    {
        use HasApiTokens, HasFactory, Notifiable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'name',
            'email',
            'password',
        ];

        /**
         * The attributes that should be hidden for serialization.
         *
         * @var array<int, string>
         */
        protected $hidden = [
            'password',
            'remember_token',
        ];

        /**
         * The attributes that should be cast.
         *
         * @var array<string, string>
         */
        protected $casts = [
            'email_verified_at' => 'datetime',
        ];

        public function connectsTo() {
            return $this->belongsToMany(User::class, 'connections', 'user_id', 'connected_user_id')
                ->withPivot('accepted')
                ->withTimestamps();
        }

        public function connectsFrom() {
            return $this->belongsToMany(User::class, 'connections', 'connected_user_id', 'user_id')
                ->withPivot('accepted')
                ->withTimestamps();
        }

        //People we've added that haven't accepted
        public function pendingConnectsTo() {
            return $this->connectsTo()->wherePivot('accepted', false);
        }

        //People who've added us that we haven't accepted
        public function pendingConnectsFrom() {
            return $this->connectsFrom()->wherePivot('accepted', false);
        }

        //People we've added that have accepted
        public function acceptedConnectsTo() {
            return $this->connectsTo()->wherePivot('accepted', true);
        }

        //People who've added us that we have accepted
        public function acceptedConnectsFrom() {
            return $this->connectsFrom()->wherePivot('accepted', true);
        }

        //All Confirmed
        public function confiremConnections() {
            return $this->acceptedConnectsFrom->merge($this->acceptedConnectsTo);
        }

        //All Pending
        public function pendignConnections() {
            return $this->pendingConnectsFrom->merge($this->pendingConnectsTo);
        }

    }
