import java.util.*;

public class Escalonamento {
	public static void main(String args[]) {

		Scanner sc = new Scanner(System.in);

		System.out.println("Escolha o algoritmo de escalonamento: ");
		System.out.print("1. FIFO \n2. SJF: ");
		int escolha = sc.nextInt();

		System.out.print("Digite o número de processos: ");
		int numprocessos = sc.nextInt();

		switch (escolha) {
		case 1:

			int tempoTotal = 0;
			int tempoEspera = 0;
			int tempoTurnaround = 0;

			ArrayList<Integer[]> listaprocesso = new ArrayList<>(); // Cada array é um processo
			
			for (int i = 0; i < numprocessos; i++) {
				Integer[] processos = new Integer[3];
				System.out.println("Digite o tempo de chegada:");
				processos[0] = i + 1;// Num processo
				processos[1] = sc.nextInt();// Chegada
				
				System.out.println("Digite o tempo de execução:");
				processos[2] = sc.nextInt(); // Execução
				listaprocesso.add(processos);
			}
			
			Collections.sort(listaprocesso, Comparator.comparingInt(p -> p[1]));
			// Ordena a lista de processos com base no tempo de chegada

			for (Integer[] processos : listaprocesso) {
				tempoEspera += tempoTotal - processos[1];
				tempoTurnaround += tempoTotal + processos[2] - processos[1];
				tempoTotal += processos[2];
			} // os valores dos elementos do array processos são usados para realizar os
				// cálculos dos tempos

			double tme = (double) tempoEspera / numprocessos;
			double tmp = (double) tempoTurnaround / numprocessos;

			System.out.println("TME: " + tme);
			System.out.println("TMP: " + tmp);
			break;

		case 2:

			int numdoProcesso[] = new int[numprocessos]; // Número do processo
			int tempoFin[] = new int[numprocessos]; // Tempo de finalização
			int tempoChegada[] = new int[numprocessos];
			int tempoUCP[] = new int[numprocessos];
			int tempoturnaround[] = new int[numprocessos];
			int tempoEsperas[] = new int[numprocessos];
			int completoounao[] = new int[numprocessos]; // Checar se o processo está completo ou não
			int sistempo = 0; // Tempo atual do sistema
			int qtdProcessos = 0;
			float tmes = 0;
			float tmps = 0;

			for (int i = 0; i < numprocessos; i++) {
				System.out.print("Tempo de chegada do processo " + (i + 1) + ": ");
				tempoChegada[i] = sc.nextInt();

				System.out.print("Tempo de UCP do processo: ");
				tempoUCP[i] = sc.nextInt();

				numdoProcesso[i] = i + 1;
				completoounao[i] = 0;
			}

			while (true) {
				int c = numprocessos; // C guarda o menor processo
				int min = 999; // Processo com o menor tempo de execução restante atualmente

				if (qtdProcessos == numprocessos)
					break;

				for (int i = 0; i < numprocessos; i++) { // Percorre todos os processos e seleciona o que tem o menor
															// T.E. restante
					if ((tempoChegada[i] <= sistempo) // verifica se o processo já chegou na fila de execução
							&& (completoounao[i] == 0) // verifica se o processo ainda não foi executado
							&& (tempoUCP[i] < min)) {// verifica se o T.E restante do processo atual é menor do que o
														// T.E
														// restante do processo com o menor T.E atual
						min = tempoUCP[i]; // Armazena T.E restante
						c = i; // Armazena o indice do processo
					}
				}

				if (c == numprocessos) // Garante que todos os processos sejam executados e finalizados
					sistempo++;
				else {
					tempoFin[c] = sistempo + tempoUCP[c]; // Calcular tempo de finalização
					sistempo += tempoUCP[c];// Atualiza a sistempo com o TE restante
					tempoturnaround[c] = tempoFin[c] - tempoChegada[c];
					tempoEsperas[c] = tempoturnaround[c] - tempoUCP[c];
					completoounao[c] = 1;
					qtdProcessos++;// Finalizados
				}
			}

			for (int i = 0; i < numprocessos; i++) {
				tmes += tempoEsperas[i];
				tmps += tempoturnaround[i];
			}

			System.out.println("\nTMP: " + (float) (tmps / numprocessos));
			System.out.println("TME: " + (float) (tmes / numprocessos));
		}
	}
}
