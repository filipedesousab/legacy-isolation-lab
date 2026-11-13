VMS = {
  "swarm-manager" => {
    interfaces: [{ ip: "192.168.60.20", intnet: "rede_interna" }],
    memory: 2048,
    cpus: 2,
    fqdn: "swarm-manager.local",
    playbook: "ansible/manager.yml"
  },

  "dmz" => {
    interfaces: [
      { ip: "192.168.56.10", intnet: false },
      { ip: "192.168.57.10", intnet: false },
      { ip: "192.168.60.10", intnet: "rede_interna" }
    ],
    memory: 2048,
    cpus: 2,
    playbook: "ansible/dmz.yml"
  },

  "swarm-worker" => {
    interfaces: [{ ip: "192.168.60.30", intnet: "rede_interna" }],
    memory: 2048,
    cpus: 2,
    playbook: "ansible/worker.yml"
  },

  "database" => {
    interfaces: [{ ip: "192.168.60.40", intnet: "rede_interna" }],
    memory: 2048,
    cpus: 2,
    playbook: "ansible/database.yml"
  }
}

Vagrant.configure("2") do |config|
  config.vm.box = "bento/ubuntu-26.04"
  config.ssh.insert_key = false

  # vbguest 0.32 nao funciona no Ruby 3.3
  if Vagrant.has_plugin?("vagrant-vbguest")
    config.vbguest.auto_update = false
  end

  VMS.each do |name, cfg|
    config.vm.define name do |node|
      node.vm.hostname = cfg[:fqdn] ? cfg[:fqdn] : name

      node.vm.provider "virtualbox" do |vb|
        vb.memory = cfg[:memory]
        vb.cpus = cfg[:cpus]
        vb.name = name
      end

      # Configuração de Rede (Isolamento de Interfaces)
      cfg[:interfaces].each do |net|
        if net[:intnet]
          node.vm.network "private_network", ip: net[:ip], virtualbox__intnet: net[:intnet]
        else
          node.vm.network "private_network", ip: net[:ip]
        end
      end

      # Provisionamento via Ansible (Executado de dentro da VM)
      if cfg[:playbook]
        node.vm.provision "ansible_local" do |ansible|
          ansible.playbook = cfg[:playbook]
          ansible.install = true
        end
      end
    end
  end
end
