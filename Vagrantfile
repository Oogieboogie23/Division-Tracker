# -*- mode: ruby -*-
# vi: set ft=ruby :

image="ubuntu/trusty64"
dev_dir ="./"
port = "8080"

Vagrant.configure("2") do |config|

	# Specify the base box
    config.vm.box = "#{image}"

	# Setup port forwarding
	  config.vm.network :forwarded_port, guest: 80, host: "#{port}", auto_correct: true

    # Setup synced folder
    config.vm.synced_folder "#{dev_dir}", "/var/www/tracker/"



    # Shell provisioning
    config.vm.provision "shell" do |s|
    	s.path = "provision/setup_apache.sh"
    end
end
