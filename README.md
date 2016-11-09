# senior-design-liccb
Project work done for Long Island City Community Boathouse

## Requirements
1. Vagrant
2. PHP + Composer (Optional: To Add Dependencies)

## Setup
1. Replace the folders map in `dev/Homestead.yaml` to point to the absolute path to `dev/liccb-event-manager` on your local machine
2. (Optional) Add `192.168.10.10 liccb-event-manager.dev` to your local `hosts` file 

## Running
1. 'git pull origin master' to get most recent changes
2. Run `vagrant up`
3. Access website at `192.168.10.10` or `liccb-event-manager.dev` from your web browser if you added it to your hosts
4. Make whatever changes necessary to local files and check website for changes
5. Run `vagrant halt` when finished making changes
6. Push changes if necessary
