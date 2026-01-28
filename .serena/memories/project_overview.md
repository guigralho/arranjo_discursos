# Arranjo de Discursos - Visão Geral do Projeto

## Propósito
Sistema de gerenciamento de arranjo de discursos para congregações das Testemunhas de Jeová. Permite organizar:
- **Oradores (Speakers)**: Cadastro e gerenciamento de oradores
- **Discursos (Speeches)**: Catálogo de discursos numerados
- **Programação (Schedules)**: Agendamento mensal de discursos
- **Envio e Recebimento de Oradores**: Coordenação entre congregações
- **Transporte (Bus/Passengers)**: Gerenciamento de passageiros para eventos

## Domínio Principal
- **Speaker**: Orador que faz discursos
- **Speech**: Discurso numerado do catálogo
- **SpeakerSpeech**: Relação N:N entre oradores e discursos que eles fazem
- **Schedule**: Programação mensal
- **SendSpeakers**: Oradores enviados para outras congregações
- **ReceiveSpeakers**: Oradores recebidos de outras congregações
- **Bus**: Ônibus para transporte
- **Passenger**: Passageiros do ônibus

## Multi-Tenancy
O sistema usa `congregation_id` para isolar dados por congregação:
- Todos os models principais usam `CongregationScope` (global scope)
- O `congregation_id` é automaticamente atribuído ao criar registros
- Usuários pertencem a uma congregação específica

## Funcionalidades Principais
1. Cadastro de oradores e seus discursos
2. Criação de programações mensais
3. Agendamento de discursos (enviar/receber oradores)
4. Exportação em PDF e CSV
5. Gerenciamento de transporte para eventos
