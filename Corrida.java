import java.util.concurrent.ThreadLocalRandom;

public class Corrida {
    public static void main(String[] args) {
        int numCarros = 5;
        int voltasTotais = 20;

        Carro[] carros = new Carro[numCarros];

        for (int i = 0; i < numCarros; i++) {
            carros[i] = new Carro("Carro " + (i + 1), voltasTotais);
            carros[i].start();
        }

        for (int i = 0; i < numCarros; i++) {
            try {
                carros[i].join();
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }

        int voltasMax = 0;
        Carro vencedor = null;
        for (int i = 0; i < numCarros; i++) {
            Carro carro = carros[i];
            if (carro.getVoltasCompletas() >= voltasMax) {
                voltasMax = carro.getVoltasCompletas();
                vencedor = carro;
            }
        }

        System.out.println("\n\nO carro vencedor é: " + vencedor.getNome());
        System.out.println("Voltas completas: " + voltasMax + "\n\n");

        for (int i = 0; i < numCarros; i++) {
            Carro carro = carros[i];
            System.out.println("Carro " + (i + 1) + " completou " + carro.getVoltasCompletas() + " voltas");
        }
    }
}

class Carro extends Thread {
    private String nome;
    private int voltasTotais;
    private int voltasCompletas;

    public Carro(String nome, int voltasTotais) {
        this.nome = nome;
        this.voltasTotais = voltasTotais;
        this.voltasCompletas = 0;
    }

    public String getNome() {
        return nome;
    }

    public int getVoltasCompletas() {
        return voltasCompletas;
    }

    @Override
    public void run() {
        while (voltasCompletas < voltasTotais) {
            int avanco = ThreadLocalRandom.current().nextInt(1, 4);
            voltasCompletas += avanco;

            System.out.println(nome + " completou " + voltasCompletas + " voltas");

            try {
                Thread.sleep(50);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        } 
    }
}
